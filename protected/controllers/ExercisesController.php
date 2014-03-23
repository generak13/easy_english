<?php

class ExercisesController extends Controller
{
	public function actionGetWords($type)
	{
		$user = Yii::app()->user;
		
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
    }
    
    echo CJavaScript::jsonEncode($data);
    Yii::app()->end();
	}
  
	public function actionList()
	{
		$this->render('list');
	}
	
  public function actionGetExcerciseList() {
    $data = array('exercises' => array());
    
    $exercises = Exercise::model()->findAll();
    
    foreach ($exercises as $exercise) {
      $data['exercises'][] = array(
        'title' => $exercise->name,
        'type' => $exercise->name
      );
    }
    
		echo CJavaScript::jsonEncode($data);
    return;
  }

	public function actionProcessResults()
	{
    $type = $_POST['type'];
    $results = $_POST['results'];
		$user = Yii::app()->user;
    
		try {
			Exercise::processTrainingResults($user, $type, $results);
			
			echo CJavaScript::jsonEncode(array('success' => true));
			Yii::app()->end();
		} catch (Exception $e) {
			echo CJavaScript::jsonEncode(array('success' => false));
			Yii::app()->end();
		}
	}
}