<?php

class DictionaryController extends Controller
{
  private static $wordsPerPage = 15;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Dictionary;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dictionary']))
		{
			$model->attributes=$_POST['Dictionary'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dictionary']))
		{
			$model->attributes=$_POST['Dictionary'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Dictionary');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Dictionary('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dictionary']))
			$model->attributes=$_GET['Dictionary'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Dictionary the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Dictionary::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Dictionary $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dictionary-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
  
  public function actionDictionary() {
    $text = isset($_GET['text']) ? $_GET['text'] : null;
    
    if(gettype($text) != 'string' && !is_numeric($text)) {
      $text = null;
    }
    
    $total_count = Dictionary::getTotalRecords($text);
    $dictionary = Dictionary::getRecords($text, 15);
    
    $dictionary = Dictionary::normalize_dictionary($dictionary);
		$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($dictionary), 'total' => $total_count, 'selectedPage' => 1, 'recordsPerPage' => self::$wordsPerPage), true, true);
        
    $dictionary_list = $this->renderPartial('dictionary_list', array('dictionary' => $dictionary, 'pagination' => $pagination), true, true);
    
    $this->render('dictionary', array('dictionary_list' => $dictionary_list, 'text' => $text ? $text : ''));
  }
  
  //TODO: remove save(false)
  public function actionAdd_word($word_to_add, $translation_for_word, $context = '', $content_id = null) {
    $response = array('success' => false);
    
    $content_id = is_numeric($content_id) ? (int)$content_id : null;
    
    $record_created = Dictionary::addToDictionary($word_to_add, $translation_for_word, $context, $content_id);
    
    if($record_created) {
      $response['success'] = true;  
    } else {
      $response['msg'] = 'Word is already in your dictionary';
    }

    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }

  public function actionRemove_from_dictionary($id) {
    Dictionary::model()->deleteByPk($id);
    
    $criteria = new CDbCriteria();
    $criteria->condition = '`d`.`user_id` = :user_id AND `dictionary_id` = :dictionary_id';
    $criteria->join = 'INNER JOIN `dictionary` d ON `d`.`id` = `dictionary_id`';
    $criteria->params = array(':dictionary_id' => $id, ':user_id' => Yii::app()->user->getId());
    
    Exercise2dictionary::model()->deleteAll($criteria);
    echo CJavaScript::jsonEncode(array('success' => true));
    Yii::app()->end();
  }
  
  public function actionEdit_word($dictionary_id, $new_word, $translation) {
    $response = array('success' => false);
    
    $is_edited = Dictionary::editDictionaryRecord($dictionary_id, $new_word, $translation);
    
    if($is_edited) {
      $response['success'] = true;
    } else {
      $response['msg'] = 'Word cann\'t be found';
    }
    
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
  
  public function actionGet_dictionary($text, $page) {
    $response = array('success' => false);
    
    try {
      $total_count = Dictionary::getTotalRecords($text);
      $dictionary = Dictionary::getRecords($text, self::$wordsPerPage, self::$wordsPerPage*($page-1));
    
      $dictionary = Dictionary::normalize_dictionary($dictionary);
      
      if($page > ceil($total_count / self::$wordsPerPage)) {
        $page = 1;
      }
			
			$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($dictionary), 'total' => $total_count, 'selectedPage' => $page, 'recordsPerPage' => self::$wordsPerPage), true, true);
      
      $response['success'] = true;
      $response['content'] = $this->renderPartial('dictionary_list', array('dictionary' => $dictionary, 'pagination' => $pagination), true, true);
      $response['page'] = $page;
    }catch(Exception $e) {}
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
  
  public function actionGet_translations($text) {
    $response = array('success' => false);
    
		try {
			$translations = Dictionary::getTranslation($text);
			$response['success'] = true;
			$response['data'] = $translations;
		} catch (Exception $e) {
			$response['msg'] = 'Internal error';
		}
    
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
  
  public function actionGet_learned_words_by_content($content_id) {
    $response = array('success' => false);
    
    $dictionary = Dictionary::model()->with('word', 'translation')->findAll(
        "user_id=:user_id AND content_id=:content_id",
        array(
          ':user_id' => Yii::app()->user->getId(),
          ':content_id' => $content_id
        )
    );
    $dictionary = Dictionary::normalize_dictionary($dictionary);
    
    $response['success'] = true;
    $response['data'] = $dictionary;
    
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
	
	public function actionTranslationsList() {
		$criteria = new CDbCriteria();
		$criteria->addCondition('`verified_date` IS NULL');
		
		$total = Transation::getTotalRecords(null, true);
		$translations = Transation::getRecords();
		
		$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($translations), 'total' => $total, 'selectedPage' => 1, 'recordsPerPage' => self::$wordsPerPage), true, true);
		$translationsTable = $this->renderPartial('translation-list', array('translations' => $translations, 'pagination' => $pagination), true, true);
		
		$this->render('translationsList', array('translationsTable' => $translationsTable));
	}
	
	public function actionGetTranslationsList($text = null, $page = null, $showAll = null) {
		$response = array('success' => false);

		try{
			$page = $page ? $page : 1;
			
			$total = Transation::getTotalRecords($text, !filter_var($showAll, FILTER_VALIDATE_BOOLEAN));
			$translations = Transation::getRecords($text, !filter_var($showAll, FILTER_VALIDATE_BOOLEAN), self::$wordsPerPage, self::$wordsPerPage * ($page - 1));
			
			$pagination = $this->renderPartial('//shared/pagination', array('recordsCount' => count($translations), 'total' => $total, 'selectedPage' => $page, 'recordsPerPage' => self::$wordsPerPage), true, true);
			$translationsTable = $this->renderPartial('translation-list', array('translations' => $translations, 'pagination' => $pagination), true, true);
		
			$response['success'] = true;
			$response['content'] = $translationsTable;
			$response['page'] = $page;
		} catch(Exception $e) {
			$response['msg'] = Yii::t('errors', $e->getTraceAsString());
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionAppoveDissaproveTranslation($id, $verified) {
		$response = array('success' => false);
		
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`id` = :id');
			$criteria->params = array(':id' => $id);

			$translation = Transation::model()->find($criteria);

			if($translation) {
				$translation->verified_date = filter_var($verified, FILTER_VALIDATE_BOOLEAN) ? date("Y-m-d H:i:s") : null;
				$translation->verified_user = Yii::app()->user->getId();
				$translation->save();
			}
			
			$response['success'] = true;
		} catch (Exception $e) {
			$response['msg'] = Yii::t('error', 'Internal error');
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionSaveTranslation($id, $text) {
		$response = array('success' => false);
		
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`id` = :id');
			$criteria->params = array(
				':id' => $id
			);
			
			$translaiton = Transation::model()->find($criteria);

			if($translaiton) {
				$translaiton->text = $text;
				$translaiton->save();
			}
			
			$response['success'] = true;
		} catch (Exception $e) {
			$response['msg'] = Yii::t('errors', 'Internal error');
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionRemoveTranslation($id) {
		$response = array('success' => false);
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionLoadWordData($id) {
		$response = array('success' => false);
		
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`user_id` = :user_id');
			$criteria->addCondition('`t`.`word_id` = :word_id');

			$criteria->params = array(
				':user_id' => Yii::app()->user->id,
				':word_id' => $id
			);

			$data = Dictionary::model()->with('translation')->findAll($criteria);

			$result = array();
			
			foreach ($data as $elem) {
				$result[] = array(
					'dictionary_id' => $elem->id,
					'translation_id' => $elem->translation->id,
					'text' => $elem->translation->text,
					'image_url' => $elem->image_url,
					'context' => $elem->context
				);
			}
			
			$response['success'] = true;
			$response['data'] = $result;
		}  catch (Exception $e) {
			$response['msg'] = 'Internal error';
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionSaveDictionaryTranslation($dictionary_id, $image_url, $context) {
		$response = array('success' => false);
		
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`user_id` = :user_id');
			$criteria->addCondition('`id` = :id');
			$criteria->params = array(
				':user_id' => Yii::app()->user->id,
				':id' => $dictionary_id
			);
			
			$dictionary = Dictionary::model()->find($criteria);
			
			if($dictionary) {
				$dictionary->image_url = $image_url ? $image_url : NULL;
				$dictionary->context = $context;
				$dictionary->save();
				
				$response['success'] = true;
			} else {
				$response['msg'] = 'Data was not found';
			}
		} catch (Exception $e) {
			$response['msg'] = 'Internal error occured';
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
	
	public function actionDeleteDictionaryTranslation($id) {
		$response = array('success' => false);
		
		try {
			$criteria = new CDbCriteria();
			$criteria->addCondition('`id` => :id');
			$criteria->addCondition('`user_id` => :user_id');
			$criteria->params = array(
				':id' => $id,
				':user_id' => Yii::app()->user->id
			);
			
			$dictionary = Dictionary::model()->find($criteria);
			
			if($dictionary) {
				$criteria = new CDbCriteria();
				$criteria->addCondition('`user_id` = :user_id');
				$criteria->addCondition('`word_id` = :word_id');
				$criteria->params = array(
					':user_id' => Yii::app()->user->id,
					':word_id' => $dictionary->word_id
				);
				
				$recordsWithTheSameTranslationCount = Dictionary::model()->count($criteria);
				
				if($recordsWithTheSameTranslationCount > 1) {
					$dictionary->delete();
					$response['success'] = true;
				} else {
					$response['msg'] = 'Last translation cann\'t be deleted';
				}
			} else {
				$response['msg'] = 'Translation was not found';
			}
		} catch (Exception $ex) {
			$response['msg'] = 'Internal error occured';
		}
		
		echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
	}
}
