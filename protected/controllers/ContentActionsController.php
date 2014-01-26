<?php

class ContentActionsController extends Controller
{
  const records_per_page = 10;
  
  public $defaultAction = 'list';
  
  public function rules() {
    
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
        
        $type = Content::$TYPE_TEXT;
        
        switch ($model->type) {
          case 'text':
            $type = Content::$TYPE_TEXT;
            break;
          case 'video':
            $type = Content::$TYPE_VIDEO;
            break;
          case 'audio':
            $type = Content::$TYPE_AUDIO;
            break;
        }
        
        $content->type = $type;
        
        if($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) {
          $content->player_link = $model->player_link;
        }
        
        if($model->genre == 'genre-1') {
          $content->genre = 1;
        } else {
          $content->genre = 2;
        }
        
        $lvl = 1;
        
        switch ($model->lvl) {
          case 'easy':
            $lvl = 1;
            break;
          case 'medium':
            $lvl = 2;
            break;
          case 'hard':
            $lvl = 3;
            break;
        }
        
        $content->lvl = $lvl;
        $content->owner_id = Yii::app()->user->getId();
        
        $words = explode(' ', $content->text);
        $pages = ceil(count($words)/Content::$WORDS_PER_PAGE);
        
        $content->pages = $pages;
        $content->save();
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    
    $this->render('create', array('model' => $model));
  }
  
  public function actionShow($id) {
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
      
      $groups_by_dot = explode("\n", $content->text);
      
      foreach ($groups_by_dot as $key => $value) {
        $words = explode(' ', $value);
        
        foreach ($words as $index => $word) {
          $words[$index] = '<tran>' . $word . '</tran>';
        }
        
        $words = implode(" ", $words);
        $groups_by_dot[$key] = '<context>' . $words . '</context>';
      }
      
      $content->text = implode("\n", $groups_by_dot);
      $content->text = nl2br($content->text);
      $this->render('show', array('content' => $content, 'var1' => 'asd'));
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
    $condition = '';
    $params = array();
    
    if(isset($_GET['title']) && $_GET['title']) {
      $title = addcslashes($_GET['title'], '%_');
      $condition .= 'title LIKE :title';
      $params[':title'] = "%$title%";
    }
    
    if(isset($_GET['genre']) && $_GET['genre']) {
      $condition .= empty($condition) ? '' : ' AND ';
      $condition .= 'genre=:genre';
      $params[':genre'] = (int)0;//$_GET['genre'];
    }
    
    if(isset($_GET['lvl']) && $_GET['lvl']) {
      $condition .= empty($condition) ? '' : ' AND ';
      $condition .= 'lvl=:lvl';
      
      $params[':lvl'] = $_GET['lvl'];
    }
    
    if(isset($_GET['content_status']) && $_GET['content_status'] == 'learned') {
      $contents = Content::model()->with(array(
          'user2content' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2content.status=0'
          )
        ))->findAll($condition, $params);
    } else if(isset($_GET['content_status']) && $_GET['content_status'] == 'learning') {
      $contents = Content::model()->with(array(
          'user2content' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2content.status=1'
          )
        ))->findAll($condition, $params);
    } else {
      $contents = Content::model()->findAll($condition, $params);
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
  
}