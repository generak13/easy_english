<?php

class ApiController extends Controller {
	
	const INTERNAL_ERROR = 0;
	const ERROR_USER_NOT_FOUND = 1;
	const API_KEY_IS_NOT_VALID = 2;
	const INVALID_DATA = 3;
	const DATA_NOT_FOUND = 4;

	public function actionGetApiKey($login, $password) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('`login` = :login');
		$criteria->addCondition('`password` = :password');
		$criteria->params = array(
			':login' => $login,
			':password' => md5($password)
		);

		$user = user::model()->find($criteria);

		if (!$user) {
			$this->error(self::ERROR_USER_NOT_FOUND);
		}

		$this->success(array('api_key' => $user->api_key));
	}

	//TODO: validate args
	public function actionGetContentsList($api_key, $count = 10, $offset = 0, $title = null, $type = null, $genre = null, $lvl = null, $date_sort = 'ASC') {
		$this->checkApiKey($api_key);

		if ($count < 0 || $count > 20) {
			$this->error(self::INVALID_DATA);
		}

		$criteria = new CDbCriteria();
		$params = array();
		$criteria->limit = $count;
		$criteria->together = true;
		$criteria->order = "`date` $date_sort";

		if ($offset) {
			$criteria->offset = $offset;
		}

		if ($title) {
			$criteria->addSearchCondition('title', $title);
		}

		if ($type) {
			$type = $this->getContentType($type);

			if ($type === false) {
				$this->error(self::INVALID_DATA);
			}

			$criteria->addCondition("`type` = ':type'");
			$params[':type'] = $type;
		}

		if ($genre) {
			$criteria->addColumnCondition("`genre` = ':genre'");
			$params[':genre'] = $genre;
		}

		if ($lvl) {
			$criteria->addColumnCondition("`lvl` = ':lvl'");
			$params[':lvl'] = $lvl;
		}

		$contents = Content::model()->findAll($criteria);

		$data = array();

		foreach ($contents as $content) {
			$data[] = array(
				'id' => $content->id,
				'title' => $content->title,
				'genre' => $content->genre,
				'lvl' => $content->lvl,
				'date' => $content->date
			);
		}

		$this->success($data);
	}
	
	public function actionGetContentData($api_key, $id) {
		$this->checkApiKey($api_key);
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('`id` = :id');
		$criteria->params = array(':id' => $id);
		
		$content = Content::model()->find($criteria);
		
		if(!$content) {
			$this->error(self::DATA_NOT_FOUND);
		}
		
		$this->success(array(
			'id' => $content->id,
			'title' => $content->title,
			'ownerId' => $content->owner_id,
			'type' => $content::getTextType($content->type),
			'genre' => $content->genre,
			'text' => $content->text,
			'lvl' => $content::getTextLevel($content->lvl),
			'pages' => $content->pages,
			'player_link' => $content->player_link,
			'date' => $content->date
		));
	}

	private function checkApiKey($api_key) {
		$user = user::model()->find("`api_key` = '" . $api_key . "'");

		if (!$user) {
			$this->error(self::API_KEY_IS_NOT_VALID);
		}
	}

	private function success($data) {
		$response = array('success' => true, 'data' => $data);

		echo CJavaScript::jsonEncode($response);
		Yii::app()->end();
	}

	private function error($errorCode) {
		$response = array(
			'success' => false,
			'error' => $errorCode,
			'msg' => $this->getErrorMessage($errorCode)
		);

		echo CJavaScript::jsonEncode($response);
		Yii::app()->end();
	}

	private function getErrorMessage($erroCode) {
		$msg = '';

		switch ($erroCode) {
			case self::INTERNAL_ERROR:
				$msg = 'Internal error';
				break;
			case self::ERROR_USER_NOT_FOUND:
				$msg = 'User not found';
				break;
			case self::API_KEY_IS_NOT_VALID:
				$msg = 'API key is not valid';
				break;
			case self::INVALID_DATA:
				$msg = 'Data is not valid';
				break;
			case self::DATA_NOT_FOUND:
				$msg = 'Data not found';
				break;
			default:
				$msg = 'Internal error';
				break;
		}

		return $msg;
	}

	private function getContentType($type_str) {
		$type = false;

		switch ($type) {
			case 'video':
				$type = 1;
				break;
			case 'audio':
				$type = 2;
			case 'text':
				$type = 3;
				break;
			default:
				$type = false;
				break;
		}

		return $type;
	}

}
