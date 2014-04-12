<?php

class ExercisesController extends Controller
{
	public function actionGetWords($type)
	{
		$user = Yii::app()->user;
		
		$criteria = new CDbCriteria();
		$criteria->addCondition('`name` = :name');
		$criteria->params = array(
			':name' => $type
		);
		
		$exercise = Exercise::model()->find($criteria);
		
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
      case 'Sprint':
        $data = Exercise::getWordsSprint($user, $exercise->id);
        break;
      case 'DoIKnow':
        $data = Exercise::getWordsDoIKnow($user, $exercise->id);
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
	
	public function actionRepairDataBase() {
		$dictionary = Dictionary::model()->findAll();
		$exercises = Exercise::model()->findAll();
		
		foreach ($exercises as $exercise) {
			foreach ($dictionary as $row) {
				$criteria = new CDbCriteria();
				$criteria->addCondition('`dictionary_id` = :dictionary_id');
				$criteria->addCondition('`exercise_id` = :exercise_id');
				$criteria->params = array(
					'dictionary_id' => $row->id,
					'exercise_id' => $exercise->id
				);
				
				$elem = Exercise2dictionary::model()->find($criteria);
				
				if(!$elem) {
					$elem = new Exercise2dictionary();
					$elem->dictionary_id = $row->id;
					$elem->exercise_id = $exercise->id;
					$elem->status = 0;
					$elem->last_learned_date = date('Y-m-d');
					
					$elem->save();
				}
			}
		}
	}
}