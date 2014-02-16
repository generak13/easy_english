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
    
    $dictionary = $this->normalize_dictionary($dictionary);
        
    $dictionary_list = $this->renderPartial('dictionary_list', array('dictionary' => $dictionary, 'total' => $total_count, 'words_per_page' => self::$wordsPerPage, 'selected_page' => 1), true, true);
    
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
    
      $dictionary = $this->normalize_dictionary($dictionary);
      
      if($page > ceil($total_count / self::$wordsPerPage)) {
        $page = 1;
      }
      
      $response['success'] = true;
      $response['content'] = $this->renderPartial('dictionary_list', array('dictionary' => $dictionary, 'total' => $total_count, 'words_per_page' => self::$wordsPerPage, 'selected_page' => $page), true, true);
      $response['page'] = $page;
    }catch(Exception $e) {}
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
  
  public function actionGet_translations($text) {
    $response = array('success' => false);
    
    $translation_objects = Transation::model()->with(array(
      'word' => array(
        'select' => false,
        'joinType' => "INNER JOIN",
        'condition' => "word.text = '$text'"
      )
    ))->findAll(array(
      'select' => 'text',
      'distinct' => true
    ));
    
    $translations = array();
    
    foreach ($translation_objects as $obj) {
      $translations[] = $obj->text;
    }

    if(count($translations) < 5) {
      $mt = MicrosoftTranslator::translate($text);
      $total_translations = array_merge($translations, $mt);
      $translations = array_unique($total_translations);
    }
    
    $translations = array_slice($translations, 0, 5);
    
    $response['success'] = true;
    $response['data'] = $translations;
    
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
    $dictionary = $this->normalize_dictionary($dictionary);
    
    $response['success'] = true;
    $response['data'] = $dictionary;
    
    echo CJavaScript::jsonEncode($response);
    Yii::app()->end();
  }
  
  private function normalize_dictionary($dictionary) {
    $result = array();
    
    foreach ($dictionary as $elem) {
      if(array_key_exists($elem->word->id, $result)) {
        $result[$elem->word->id]['translations'][] = $elem->translation->text;
        $result[$elem->word->id]['contexts'][] = $elem->context;
        
        if($elem->added_datetime > $result[$elem->word->id]['date']) {
          $result[$elem->word->id]['date'] = $elem->added_datetime;
        }
      }else {
        $result[$elem->word->id] = array(
          'dictionary_id' => $elem->id,
          'word' => $elem->word->text,
          'translations' => array($elem->translation->text),
          'contexts' => array($elem->context),
          'sound' => '/audio/' . $elem->word->text . '.mp3',
          'date' => $elem->added_datetime
        );
      }
    }
    
    usort($result, array($this, 'sort_dictionary'));
    
    return $result;
  }
  
  private function sort_dictionary($a, $b) {
    return $a['date'] < $b['date'];
  }
}
