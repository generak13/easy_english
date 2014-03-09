<?php

class ManageUsersController extends Controller
{

	private static $recordsPerPage = 15;
	
	public function actionIndex()
	{
		$text = isset($_GET['text']) ? $_GET['text'] : null;
    
    if(gettype($text) != 'string') {
      $text = null;
    }
		
		$total = user::getTotalRecords($text);
		$users = user::model()->findAll();

		$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($users), 'total' => $total, 'selectedPage' => 1, 'recordsPerPage' => self::$recordsPerPage), true, true);
		$usersList = $this->renderPartial('usersList', array('users' => $users, 'pagination' => $pagination), true, true);
		$this->render('index', array('usersList' => $usersList));
	}
	
	public function actionGetUsers($text, $page) {
    $response = array('success' => false);
    		
    try {
      $total = user::getTotalRecords($text);
      $users = user::getRecords($text, self::$recordsPerPage, self::$recordsPerPage*($page-1));
    
      if($page > ceil($total / self::$recordsPerPage)) {
        $page = 1;
      }
			
			$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($users), 'total' => $total, 'selectedPage' => $page, 'recordsPerPage' => self::$recordsPerPage), true, true);
      
      $response['success'] = true;
      $response['content'] = $this->renderPartial('usersList', array('users' => $users, 'pagination' => $pagination), true, true);
      $response['page'] = $page;
    }catch(Exception $e) {
						echo '<pre>';
			print_r($e);
			die();}
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
	
	public function actionChangeRole($user_id, $role) {
		$response = array('success' => false);
		
		if($role < 0 || $role > 2) {
			$response['msg'] = Yii::t('manageUsers', 'Not valid parameter "role"');
			echo CJavaScript::jsonEncode($response);
			Yii::app()->end();
		}
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('`id` = :user_id');
		$criteria->params = array(
			':user_id' => $user_id
		);
		
		$user = user::model()->find($criteria);
		
		if($user) {
			$user->type = $role;
			$user->save();
			
			if($user->getErrors()) {
				$response['msg'] = Yii::t('manageUsers', 'Sorry, some errors occured.');
			} else {
				$response['success'] = true;
			}
		} else {
			$response['msg'] = Yii::t('manageUsers', 'Cann\'t find specifyied user');
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
}