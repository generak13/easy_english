<?php

/**
 * This is the model class for table "exercise".
 *
 * The followings are the available columns in table 'exercise':
 * @property string $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Exercise2dictionary[] $exercise2dictionaries
 */
class Exercise extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exercise';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'exercise2dictionaries' => array(self::HAS_MANY, 'Exercise2dictionary', 'exercise_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exercise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getExercises() {
		$command = Yii::app()->db->createCommand('SELECT `name` FROM `exercise`;');
		return $command->queryColumn();
	}
	
	public static function getWordsWordTranslation($user) {
    $words = Exercise2dictionary::model()->with(array(
			'dictionary' => array(
				'condition' => 'dictionary.user_id = ' . $user->id
			)
		))->findAll(array(
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
	
	public static function getWordsTranslationWord($user) {
    $words = Exercise2dictionary::model()->with(array(
			'dictionary' => array(
				'condition' => 'dictionary.user_id = ' . $user->id
			)
			))->findAll(array(
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
        'pictureLink' => $word->dictionary->image_url,
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
	
	public static function getWordsBuildWord($user) {
    $result = array();
    
    $words = Exercise2dictionary::model()->with(array(
			'dictionary' => array(
				'condition' => 'dictionary.user_id = ' . $user->id
			)
			))->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->word->text,
        'translation' => $word->dictionary->translation->text,
        'pictureLink' => $word->dictionary->image_url,
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
	
	public static function getWordsSoundToWord($user) {
    $result = array();
    
    $words = Exercise2dictionary::model()->with(array(
			'dictionary' => array(
				'condition' => 'dictionary.user_id = ' . $user->id
			)
			))->findAll(array(
      'limit' => 10,
      'order' => 'status DESC, rand()'
    ));
    
    foreach ($words as $word) {
      $question = array(
        'id' => $word->dictionary->id,
        'phrase' => $word->dictionary->word->text,
        'pictureLink' => $word->dictionary->image_url,
        'voiceLink' => '/audio/' . $word->dictionary->word->text . '.mp3',
        'correct' => false
      );
      
      $result[] = array('question' => $question);
    }
    
    return $result;
  }
	
	public static function processTrainingResults($user, $type, $results) {
		$mapping = array();
		$points = 0;

    foreach ($results as $elem) {
			$is_correct = filter_var($elem['correct'], FILTER_VALIDATE_BOOLEAN);
      $mapping[$elem['id']] = $is_correct;
			
			if($is_correct) {
				$points++;
			}
    }
    
    $criteria = new CDbCriteria();
    $criteria->addInCondition('dictionary_id', array_keys($mapping));
    $criteria->join = 'INNER JOIN `exercise` e ON `e`.id = exercise_id';
    $criteria->addCondition("`e`.`name` = '$type'");
    
    
    $e2ds = Exercise2dictionary::model()->with(array(
			'dictionary' => array(
				'condition' => 'dictionary.user_id = ' . $user->id
				)
		))->findAll($criteria);

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
			
			Statistics::saveUserStatisic($user->id, $points);
    }
	}
}
