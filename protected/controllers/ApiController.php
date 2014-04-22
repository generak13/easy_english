<?php

class ApiController extends Controller {
	
	const INTERNAL_ERROR = 0;
	const ERROR_USER_NOT_FOUND = 1;
	const API_KEY_IS_NOT_VALID = 2;
	const INVALID_DATA = 3;
	const DATA_NOT_FOUND = 4;
	const USER_ALREADY_EXISTS = 5;

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
	public function actionGetContentsList($count = 10, $offset = 0, $title = null, $type = null, $genre = null, $lvl = null, $date_sort = 'ASC') {
		$this->checkApiKey();

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
				'type' => $content->type,
				'lvl' => $content->lvl,
				'date' => $content->date
			);
		}

		$this->success($data);
	}
	
	public function actionGetContentData($id) {
		$this->checkApiKey();
		
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
			'type' => $content->type,
			'genre' => $content->genre,
			'text' => $content->text,
			'lvl' => $content->lvl,
			'pages' => $content->pages,
			'player_link' => $content->player_link,
			'date' => $content->date
		));
	}
	
  //post
	public function actionRegisterUser() {
    if(!isset($_POST['login']) || !isset($_POST['email']) || !isset($_POST['password'])) {
      $this->error(self::INVALID_DATA);
    }
    
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
		try {
			if(user::isUserExists($login) || user::isUserExists($email)) {
				$this->error(self::USER_ALREADY_EXISTS);
			}
			
			$user = new user();
			$user->login = $login;
			$user->email = $email;
			$user->type = user::USER_COMMON;
			$user->register = date('Y-m-d H:i:s');
			$user->password = md5($password);
			$user->api_key = md5($email . microtime());
			$isCreated = $user->save();
			
			if($isCreated) {
				$this->success(array('api_key' => $user->api_key));
			} else {
				$this->error(self::INTERNAL_ERROR);
			}
		} catch(Exception $e) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionTranslate($text) {
		try {
			$this->checkApiKey();
			$translations = Dictionary::getTranslation($text);
			
			$this->success(array(
				'transltations' => $translations,
			));
		} catch (Exception $ex) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionGetUserDetails() {
		try {
      $user = $this->checkApiKey();
			$criteria = new CDbCriteria();
			$criteria->addCondition('`api_key` = :api_key');
			$criteria->params = array(
				':api_key' => $user->api_key
			);
			
			$user = user::model()->find($criteria);
			
			if(!$user) {
				$this->error(self::API_KEY_IS_NOT_VALID);
			}
			
			$this->success(array(
				'login' => $user->login,
				'email' => $user->email,
				'avatar' => "https://s.gravatar.com/avatar/" . md5($user->email) . "?s=45",
				'registration_date' => $user->register
			));
		} catch (Exception $ex) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionGetUserDictionary($text = null, $count = 10, $offset = 0) {
		try {
			$user = $this->checkApiKey();

			$limit = $count < 10 ? $count : 10;

			$dictionary = Dictionary::getRecords($text, $limit, $offset, $user->id);
			$dictionary = Dictionary::normalize_dictionary($dictionary);
			
			$this->success($dictionary);
		}  catch (Exception $e) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
  //post
	public function actionAddToDictionary() {
    if(!isset($_POST['api_key']) || !isset($_POST['eng_text']) || !isset($_POST['translation'])) {
      $this->error(self::INVALID_DATA);
    }
    
    $api_key = $_POST['api_key'];
    $eng_text = $_POST['eng_text'];
    $translation = $_POST['translation'];
    $context = isset($_POST['context']) ? $_POST['context'] : null;
    $content_id = isset($_POST['content_id']) ? $_POST['content_id'] : null;
    
		try {
			$user = $this->checkApiKey();
			
			Dictionary::addToDictionary($eng_text, $translation, $context, $content_id, $user->id);
			$this->success(array('eng_text' => $eng_text, 'translation' => $translation, 'context' => $context, 'content_id' => $content_id));
		} catch (Exception $ex) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionExercisesList() {
		try {
			$this->success(Exercise::getExercises());
		} catch (Exception $e) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionPhpinfo() {
		echo phpinfo();
	}
	
	public function actionGetTraining($type) {
		try {
			$user = $this->checkApiKey();
			
			$criteria = new CDbCriteria();
			$criteria->addCondition('`name` = :name');
			$criteria->params = array(
				':name' => $type
			);
		
			$exercise = Exercise::model()->find($criteria);
			
			if(!$exercise) {
				$this->error(self::INVALID_DATA);
			}
			
			switch($type) {
				case 'Word-Translation':
					$data = Exercise::getWordsWordTranslation($user, $exercise->id);
					break;
				case 'Translation-Word':
					$data = Exercise::getWordsTranslationWord($user, $exercise->id);
					break;
				case 'BuildWord':
					$data = Exercise::getWordsBuildWord($user, $exercise->id);
					break;
				case 'SoundToWord':
					$data = Exercise::getWordsSoundToWord($user, $exercise->id);
					break;
				default:
					throw new Exception();
					break;
			}
			
			$this->success($data);
		} catch (Exception $e) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
  
  //post
	public function actionProcessResults() {
    if(!isset($_POST['api_key']) || !isset($_POST['type']) || !isset($_POST['results'])) {
      $this->error(self::INVALID_DATA);
    }
    
    $api_key = $_POST['api_key'];
    $type = $_POST['type'];
    $results = $_POST['results'];
    
		try {
			$user = $this->checkApiKey();
			$exercises = Exercise::getExercises();
			
			if(!in_array($type, $exercises)) {
				$this->error(self::INVALID_DATA);
			}
			
			Exercise::processTrainingResults($user, $type, $results);
			
			$this->success();
		} catch (Exception $ex) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	private function checkApiKey() {
    if(!isset($_GET['api_key']) && !isset($_POST['api_key'])) {
      $this->error(self::DATA_NOT_FOUND);
    }
    
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : $_POST['api_key'];

		$user = user::model()->find("`api_key` = '" . $api_key . "'");

		if (!$user) {
			$this->error(self::API_KEY_IS_NOT_VALID);
		}
		
		return $user;
	}

	private function success($data = null) {
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
			case self::USER_ALREADY_EXISTS:
				$msg = 'User already exists';
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
