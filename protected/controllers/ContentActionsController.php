<?php

class ContentActionsController extends Controller
{
  const records_per_page = 10;
  
  public $defaultAction = 'list';
  
  public function rules() {
    
  }
	
	public function accessRules()
	{   
		return array(
				array('allow',
						'actions'=>array('admin'),
						'roles'=>array('staff', 'devel'),
				),
				array('deny',  // deny all users
						'users'=>array('*'),
				),
		);
	}

  /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
    $model = new content('search');
    $model->unsetAttributes();
    
    if(isset($_GET['content'])) {
      $model->attributes = $_GET['content'];
      $model->date = $_GET['type'][1];
      
      $this->renderPartial('content_list_grid',array(
            'model'=>$model,
        ));
      return;
    }
    
    $model->type = 2;
    
		$this->render('index', array('model' => $model));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
  
  public function actionCreate() {
    $model = new CreateContentForm();

    if(isset($_POST['CreateContentForm'])) {
      $model->attributes = $_POST['CreateContentForm'];
      
      if($model->validate()) {
        $content = new Content();
        $content->title = $model->title;
        $content->text = $model->text;
        
        $content->type = $content->getTypeByText($model->type);
        
        if($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) {
          $content->player_link = $model->player_link;
        }
        
        if($model->genre == 'genre-1') {
          $content->genre = 1;
        } else {
          $content->genre = 2;
        }
        
        $content->lvl = $content->getLevelByText($model->lvl);
        $content->owner_id = Yii::app()->user->getId();
        
        $pages = $content->calculatePagesCount($content);
        
        $content->pages = $pages;
        $content->date = date('Y-m-d H:i:s');
        $content->save();
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    
    $this->render('create', array('model' => $model, 'action' => 'create'));
  }
	
	public function actionEdit($id = 0) {
		$id = isset($_POST['CreateContentForm']) ? (int)$_POST['CreateContentForm']['identifier'] : $id;
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('id=:id');
		$criteria->addCondition('owner_id=:owner_id');
		$criteria->params = array(
			':id' => $id,
			':owner_id' => Yii::app()->user->id
		);
		$content = Content::model()->find($criteria);
		
		if(!$content) {
			$this->redirect('/contentAction/show/' . $id);
		}
		
		$model = new CreateContentForm();

    if(isset($_POST['CreateContentForm'])) {
      $model->attributes = $_POST['CreateContentForm'];
      
      if($model->validate()) {
        $content->title = $model->title;
        $content->text = $model->text;
        
        $content->type = $content->getTypeByText($model->type);
        
        if($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) {
          $content->player_link = $model->player_link;
        }
				
        $content->genre = $content->getGenreByText($model->genre);
        
        $content->lvl = $content->getLevelByText($model->lvl);
        $content->owner_id = Yii::app()->user->getId();
        
        $pages = $content->calculatePagesCount($content);
        
        $content->pages = $pages;
        $content->date = date('Y-m-d H:i:s');
        $content->save();
        $this->redirect('/contentActions/list');
      }
    } else {
			$model->identifier = $content->id;
			$model->title = $content->title;
			$model->genre = Content::getTextGenre($content->genre);
			$model->type = Content::getTextType($content->type);
			$model->player_link = $content->player_link;
			$model->lvl = Content::getTextLevel($content->lvl);
			$model->text = $content->text;
		}

		$this->render('create', array('model' => $model, 'action' => 'edit'));
	}
  
  public function actionShow($id, $page = 1) {
    $content = Content::model()->find('id=:id', array(':id' => $id));

    if($content) {
      $user2content = User2content::model()->find('user_id=:user_id AND content_id=:content_id', array(':user_id' => Yii::app()->user->getId(), ':content_id' => $id));
      
      if(!$user2content) {
        $user2content = new User2content();
        $user2content->user_id = Yii::app()->user->getId();
        $user2content->content_id = $id;
        $user2content->status = 0;
        $user2content->save();
      }
			
			$text = $content->getTextByPage($page);
      $contexts = $content->getContexts($text);

			foreach ($contexts as $key => $value) {
        $words = explode(' ', $value);

				foreach ($words as $index => $word) {
					$words[$index] = '<tran>' . trim($word) . '</tran>';
        }
				
        $words = implode(" ", $words);
        $contexts[$key] = '<context>' . $words . '</context>';
      }

      $content->text = implode("\n", $contexts);
      $content->text = nl2br($content->text);

			$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => 1, 'total' => $content->pages, 'selectedPage' => $page, 'recordsPerPage' => 1), true, true);
      
			if(Yii::app()->request->isAjaxRequest) {
				$response = array('success' => true);
				$response['data'] = array(
					'content' => $content->text,
					'pagination' => $pagination
				);
				
				echo CJavaScript::jsonEncode($response);
				return;
			}
			
			$textContent = $this->renderPartial('textContent', array('text' => $content->text, 'isTextType' => $content->type == Content::$TYPE_TEXT, 'isLearned' => $user2content->status == 1, 'pagination' => $pagination), true, true);
			$this->render('show', array('content' => $content, 'textContent' => $textContent));
    }
  }
  
  public function actionSet_learned($id) {
    $response = array('success' => false);
    
    $user2content = User2content::model()->find('user_id=:user_id AND content_id=:content_id', array(':user_id' => Yii::app()->user->getId(), ':content_id' => $id));
    
    if($user2content) {
      $user2content->status = 1;
      $user2content->save();
      
      $response['success'] = true;
    }
    
    echo CJavaScript::jsonEncode($response);
    return;
  }
  
  public function actionList() {
    $this->render('index', array('contents' => array(), 'pages' => 0));
  }
  
  public function actionGet_contents() {
    $response = array('success' => true);
    $criteria = new CDbCriteria();

    if(isset($_GET['title']) && $_GET['title']) {
      $title = addcslashes($_GET['title'], '%_');
			$criteria->addSearchCondition('title', $title);
    }
    
    if(isset($_GET['type']) && $_GET['type'] != 'all') {
      $type = $this->getType($_GET['type']);

      if($type) {
        $criteria->addCondition('type = ' . $type);
      }
    }
    
    if(isset($_GET['genre']) && $_GET['genre']) {
      $criteria->addInCondition('genre', $this->get_genre($_GET['genre']));
    }
    
    if(isset($_GET['lvl'])) {
      $criteria->addInCondition('lvl', $this->get_lvl($_GET['lvl']));
    }
    
    if(isset($_GET['content_status']) && $_GET['content_status'] == 'learned') {
      $contents = Content::model()->with(array(
          'user2contents' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2contents.status=0'
          )
        ))->findAll($criteria);
    } else if(isset($_GET['content_status']) && $_GET['content_status'] == 'learning') {
      $contents = Content::model()->with(array(
          'user2contents' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2contents.status=1'
          )
        ))->findAll($criteria);
    } else {
      $contents = Content::model()->findAll($criteria);
    }

    $contents_count = count($contents);
    $grid = $this->renderPartial('contents_grid', array('models' => $contents, 'pages' => $contents_count/self::records_per_page), true);
    
    $response['content_table'] = $grid;
    echo CJavaScript::jsonEncode($response);
    return;
  }
  
  public function actionGet_sentence_translation($text) {
    $response = array('success' => false);
    
    $translation = MicrosoftTranslator::translate($text);
    
    if($translation) {
      $response['success'] = true;
      $response['data'] = $translation[0];
    } else {
      $response['msg'] = 'Some errors';
    }
    
    echo CJavaScript::jsonEncode($response);
    return;
  }
	
	public function actionContentsList() {
		
	}
  
  private function get_genre($genre_arr) {
    $genre = array();
    
    foreach ($genre_arr as $elem) {
      switch ($elem) {
        case 'tourism': 
          $genre[] = 0;
          break;
        case 'IT': 
          $genre[] = 1;
          break;
        case 'rest': 
          $genre[] = 2;
          break;
        default: 
          $genre[] = 3;
          break;
      }
    }
    
    return $genre;
  }
  
  private function get_lvl($lvl_arr) {
    $lvl = array();
    
    foreach ($lvl_arr as $elem) {
      switch ($elem) {
        case '0': 
          $lvl[] = 0;
          break;
        case '1': 
          $lvl[] = 1;
          break;
        case '2': 
          $lvl[] = 2;
          break;
        default: 
          $lvl[] = 0;
          break;
      }
    }
    
    return $lvl;
  }
  
  private function getType($input_type) {
    switch ($input_type) {
      case 'video':
        $input_type = Content::$TYPE_VIDEO;
        break;
      case 'audio':
        $input_type = Content::$TYPE_AUDIO;
        break;
      case 'text':
        $input_type = Content::$TYPE_TEXT;
        break;
      default:
        $input_type = 0;
        break;
    }
    
    return $input_type;
  }
  
	
}