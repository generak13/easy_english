<?php

class ExercisesController extends Controller
{
	public function actionGetWords($type)
	{
    switch($type) {
      case 'Word-Translation':
        $data = $this->getWordsWordTranslation();
        break;
      case 'Translation-Word':
        $data = $this->getWordsTranslationWord();
        break;
      case 'BuildWord':
        $data = $this->getWordsBuildWord();
        break;
      case 'SoundToWord':
        $data = $this->getWordsSoundToWord();
        break;
    }
    
    echo CJavaScript::jsonEncode($data);
    Yii::app()->end();
	}
  
  private function getWordsWordTranslation() {
    $words = Exercise2dictionary::model()->with('dictionary')->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    $all_answers = Dictionary::model()->with('translation')->findAll(array(
      'select' => '*',
      'limit' => 10,
      'order' => 'rand()'
    ));
    
    $result = array();
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->word->text,
        'pictureLink' => $word->dictionary->image_url,
        'context' => $word->dictionary->context,
        'voiceLink' => '/audio/' . $word->dictionary->word->text . '.mp3',
        'answerId' => $word->dictionary->translation->id,
        'correct' => false
      );
      
      $answers = array(array(
        'id' => $word->dictionary->translation->id, 
        'phrase' => $word->dictionary->translation->text
      ));
      
      while(true) {
        $elem = array_rand($all_answers);
        $elem = $all_answers[$elem];
        
        $tmp = array('id' => $elem->translation_id, 'phrase' => $elem->translation->text);
        
        if(!in_array($tmp, $answers) && !$elem->translation_id != $word->dictionary->translation_id) {
          $answers[] = $tmp;
          
          if(count($answers) == 5) {
            shuffle($answers);
            break;
          }
        }
      }
      
      $result[] = array(
        'question' => $question,
        'answers' => $answers
      );
    }
    
    return $result;
  }
  
  private function getWordsTranslationWord() {
    $words = Exercise2dictionary::model()->with('dictionary')->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    $all_answers = Dictionary::model()->with('word')->findAll(array(
      'select' => '*',
      'limit' => 10,
      'order' => 'rand()'
    ));
    
    $result = array();
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->translation->text,
        'pictureLink' => 'empty',
        'voiceLink' => '/audio/' . $word->dictionary->word->text . '.mp3',
        'answerId' => $word->dictionary->word->id,
        'correct' => false
      );
      
      $answers = array(array(
        'id' => $word->dictionary->word->id, 
        'phrase' => $word->dictionary->word->text
      ));
      
      while(true) {
        $elem = array_rand($all_answers);
        $elem = $all_answers[$elem];
        
        $tmp = array('id' => $elem->word_id, 'phrase' => $elem->word->text);
        
        if(!in_array($tmp, $answers) && !$elem->word_id != $word->dictionary->word_id) {
          $answers[] = $tmp;
          
          if(count($answers) == 5) {
            shuffle($answers);
            break;
          }
        }
      }
      
      $result[] = array(
        'question' => $question,
        'answers' => $answers
      );
    }
    
    return $result;
  }
  
  private function getWordsBuildWord() {
    $result = array();
    
    $words = Exercise2dictionary::model()->with('dictionary')->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->word->text,
        'translation' => $word->dictionary->translation->text,
        'pictureLink' => 'empty',
        'voiceLink' => '/audio/' . $word->dictionary->word->text . '.mp3',
        'answerId' => $word->dictionary->translation->id,
        'correct' => false
      );
      
      $symbols = str_split($word->dictionary->word->text);
      shuffle($symbols);
      
      $result[] = array('question' => $question, 'symbols' => $symbols, 'currentAnswer' => '');
    }
    
    return $result;
  }
  
  private function getWordsSoundToWord() {
    $result = array();
    
    $words = Exercise2dictionary::model()->with('dictionary')->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->word->text,
        'pictureLink' => 'empty',
        'voiceLink' => '/audio/' . $word->dictionary->word->text . '.mp3',
        'correct' => false
      );
      
      $result[] = array('question' => $question);
    }
    
    return $result;
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
    $mapping = array();
		$points = 0;
		$userId = 0;
    
    foreach ($results as $elem) {
			$is_correct = filter_var($elem['correct'], FILTER_VALIDATE_BOOLEAN);
      $mapping[$elem['id']] = $is_correct;
			
			if($is_correct) {
				$points++;
			}
    }
    
    $dictionaryIds = array();
    
    foreach ($results as $elem) {
      $dictionaryIds[] = $elem['id'];
    }
    
    $criteria = new CDbCriteria();
    $criteria->addInCondition('dictionary_id', $dictionaryIds);
    $criteria->join = 'INNER JOIN `exercise` e ON `e`.id = exercise_id';
    $criteria->addCondition("`e`.`name` = '$type'");
    
    
    $e2ds = Exercise2dictionary::model()->findAll($criteria);

    foreach ($e2ds as $e2d) {
      $e2d->last_learned_date = date('Y-m-d H:i:s');
      
      $status = $e2d->status;
      
      if($mapping[$e2d->dictionary_id]) {
        $status = $e2d->status + 1;
      } else {
        $status = $status - $status / 4;
      }
      
      if($status > 9) {
        $status = 9;
      } else if($status < 0) {
        $status = 0;
      }
      
      $e2d->status = $status;
      $e2d->save();
			
			$userId = $e2d->dictionary->user_id;
    }
		
		if($userId) {
			Statistics::saveUserStatisic($userId, $points);
		}
		
		echo CJavaScript::jsonEncode(array('success' => true, 'user_id' => $userId));
    Yii::app()->end();
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}