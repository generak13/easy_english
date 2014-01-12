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
        $content = new content();
        $content->title = $model->title;
        $content->text = $model->text;
        $content->type = $model->type;
        
        if($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) {
          $content->player_link = $model->player_link;
        }
        
        $content->genre = $model->genre;
        $content->lvl = $model->lvl;
        $content->owner_id = Yii::app()->user->getId();
        $content->save();
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    
    $this->render('create', array('model' => $model));
  }
  
  public function actionShow($id) {
    $content = content::model()->find('id=:id', array(':id' => $id));

    if($content) {
      $user2content = User2content::model()->find('user_id=:user_id AND content_id=:content_id', array(':user_id' => Yii::app()->user->getId(), ':content_id' => $id));
      
      if(!$user2content) {
        $user2content = new User2content();
        $user2content->user_id = Yii::app()->user->getId();
        $user2content->content_id = $id;
        $user2content->status = 0;
        $user2content->save();
      }
      
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
      $contents = content::model()->with(array(
          'user2content' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2content.status=0'
          )
        ))->findAll($condition, $params);
    } else if(isset($_GET['content_status']) && $_GET['content_status'] == 'learning') {
      $contents = content::model()->with(array(
          'user2content' => array(
            'select' => false,
            'joinType' => 'INNER JOIN',
            'condition' => 'user2content.status=1'
          )
        ))->findAll($condition, $params);
    } else {
      $contents = content::model()->findAll($condition, $params);
    }

    $contents_count = count($contents);
    $grid = $this->renderPartial('contents_grid', array('models' => $contents, 'pages' => $contents_count/self::records_per_page), true);
    
    $response['content_table'] = $grid;
    echo CJavaScript::jsonEncode($response);
    return;
  }
  
}