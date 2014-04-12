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
				'type' => $content->type,
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
			'type' => $content->type,
			'genre' => $content->genre,
			'text' => $content->text,
			'lvl' => $content->lvl,
			'pages' => $content->pages,
			'player_link' => $content->player_link,
			'date' => $content->date
		));
	}
	
	public function actionRegisterUser($login, $email, $password) {
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
	
	public function actionTranslate($api_key, $text) {
		try {
			$this->checkApiKey($api_key);
			$translations = Dictionary::getTranslation($text);
			
			$this->success(array(
				'transltations' => $translations,
			));
		} catch (Exception $ex) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionGetUserDetails($api_key) {
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`api_key` = :api_key');
			$criteria->params = array(
				':api_key' => $api_key
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
	
	public function actionGetUserDictionary($api_key, $text = null, $count = 10, $offset = 0) {
		try {
			$user = $this->checkApiKey($api_key);

			$limit = $count < 10 ? $count : 10;

			$dictionary = Dictionary::getRecords($text, $limit, $offset, $user->id);
			$dictionary = Dictionary::normalize_dictionary($dictionary);
			
			$this->success($dictionary);
		}  catch (Exception $e) {
			$this->error(self::INTERNAL_ERROR);
		}
	}
	
	public function actionAddToDictionary($api_key, $eng_text, $translation, $context = null, $content_id = null) {
		try {
			$user = $this->checkApiKey($api_key);
			
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
	
	public function actionGetTraining($api_key, $type) {
		try {
			$user = $this->checkApiKey($api_key);
			$exercises = Exercise::getExercises();
			
			if(!in_array($type, $exercises)) {
				$this->error(self::INVALID_DATA);
			}
			
			switch($type) {
				case 'Word-Translation':
					$data = Exercise::getWordsWordTranslation($user);
					break;
				case 'Translation-Word':
					$data = Exercise::getWordsTranslationWord($user);
					break;
				case 'BuildWord':
					$data = Exercise::getWordsBuildWord($user);
					break;
				case 'SoundToWord':
					$data = Exercise::getWordsSoundToWord($user);
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
	
	public function actionProcessResults($api_key, $type, $results) {
		try {
			$user = $this->checkApiKey($api_key);
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
	
	private function checkApiKey($api_key) {
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
