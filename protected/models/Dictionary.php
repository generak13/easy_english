<?php

/**
 * This is the model class for table "dictionary".
 *
 * The followings are the available columns in table 'dictionary':
 * @property string $id
 * @property string $user_id
 * @property string $word_id
 * @property string $translation_id
 *  * @property integer $content_id
 * @property string $context
 * @property int $context_id
 * @property string $added_datetime
 *
 * The followings are the available model relations:
 * @property Content $content
 * @property Word $word
 * @property Transation $translation
 * @property Exercise2dictionary[] $exercise2dictionaries
 */
class Dictionary extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dictionary';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, word_id, translation_id, added_datetime', 'required'),
      array('content_id', 'numerical', 'integerOnly'=>true),
			array('user_id, word_id, translation_id', 'length', 'max'=>10),
			array('context', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, word_id, translation_id, content_id, context, added_datetime', 'safe', 'on'=>'search'),
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
      'content' => array(self::BELONGS_TO, 'Content', 'content_id'),
			'word' => array(self::BELONGS_TO, 'Word', 'word_id'),
			'translation' => array(self::BELONGS_TO, 'Transation', 'translation_id'),
			'exercise2dictionaries' => array(self::HAS_MANY, 'Exercise2dictionary', 'dictionary_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'word_id' => 'Word',
			'translation_id' => 'Translation',
      'content_id' => 'Content',
			'context' => 'Context',
			'added_datetime' => 'Added Datetime',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('word_id',$this->word_id,true);
		$criteria->compare('translation_id',$this->translation_id,true);
    $criteria->compare('content_id',$this->content_id);
		$criteria->compare('context',$this->context,true);
		$criteria->compare('added_datetime',$this->added_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dictionary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
  
  public static function addToDictionary($word_to_add, $translation_for_word, $context, $content_id) {
    //check if current user already has this word
    $dictionary = Dictionary::model()->with(array(
      'word' => array(
        'select' => false,
        'joinType' => 'INNER JOIN',
        'condition' => 'word.text="'. $word_to_add . '"'
    )))->find('user_id=:user_id', array(':user_id' => Yii::app()->user->getId()));
    
    if($dictionary) {
      return false;
    }
    
    $word = Word::model()->find('text=:text', array(':text' => $word_to_add));
    
    //check current word exists in global glossary
    if(!$word) {
      $word = new Word();
      $word->text = $word_to_add;
      $word->audio = '/audio/' . $word_to_add . '.mp3';
      $word->save();
      
      self::create_word_mp3($word_to_add);
      
      $translation = new Transation();
      $translation->word_id = $word->id;
      $translation->text = $translation_for_word;
      $translation->save();
    } else {
      $translation = Transation::model()->find("word_id=:word_id AND text=:text", array(":word_id" => $word->id, ':text' => $translation_for_word));
      
      if(!$translation) {
        $translation = new Transation();
        $translation->word_id = $word->id;
        $translation->text = $translation_for_word;
        $translation->save();
      }
    }
    
    $dictionary = new Dictionary();
    $dictionary->word_id = $word->id;
    $dictionary->translation_id = $translation->id;
    $dictionary->user_id = Yii::app()->user->getId();
    $dictionary->context = $context;
    $dictionary->content_id = $content_id;
    $dictionary->added_datetime = date('Y-m-d H:i:s');
    $dictionary->save();
    
    $exercises = Exercise::model()->findAll();
    
    foreach ($exercises as $exercise) {
      $e2d = new Exercise2dictionary();
      $e2d->exercise_id = $exercise->id;
      $e2d->dictionary_id = $dictionary->id;
      $e2d->status = 0;
      $e2d->last_learned_date = NULL;
      $e2d->save();
    }
    
    return true;
  }
  
  public static function editDictionaryRecord($dictionary_id, $new_word, $translation) {
    $dictionary = Dictionary::model()->with('word', 'translation')->findByPk($dictionary_id);
    
    if(!$dictionary) {
      return false;
    }
    
    $word = Word::model()->find("id=:id AND text=:text", array(':id' => $dictionary->word_id, ':text' => $new_word));
    $translation_id = $dictionary->translaton_id;

    if(!$word) {
      $word = new Word();
      $word->text = $new_word;
      $word->audio = '/audio/' . $new_word . '.mp3';
      
      self::create_word_mp3($new_word);
      
      if($dictionary->translation->text != $translation) {
        $translation = new Transation();
        $translation->word_id = $word->id;
        $translation->text = $translation;
        $translation->save();
        
        $translation_id = $translation->id;
      }
    }
    
    $word->text = $new_word;
    $word->save();
    
    $dictionary->word_id = $word->id;
    $dictionary->translation_id = $translation_id;
    $dictionary->save();
  }

  public static function getTotalRecords($text) {
    if(isset($text)) {
      return Dictionary::model()->with(array(
        'word' => array(
          'condition' => "word.text LIKE '%$text%'"
        )))->countByAttributes(array('user_id' => Yii::app()->user->getId()));
    } else {
      return Dictionary::model()->countByAttributes(array('user_id' => Yii::app()->user->getId()));
    }
  }
  
  public static function getRecords($text, $limit = null, $offset = null) {
    $criteria = new CDbCriteria();
    $criteria->addCondition('user_id=:user_id');
    $criteria->params = array(':user_id' => Yii::app()->user->getId());
    
    if($limit) {
      $criteria->limit = $limit;
      $criteria->together = true;
    }
    
    if($offset) {
      $criteria->offset = $offset;
    }
    
    if(isset($text)) {
      return Dictionary::model()->with(array(
        'word' => array(
          'condition' => "word.text LIKE '%$text%'"
        ), 
        'translation'))->findAll($criteria);
    } else {
      return Dictionary::model()->with('word', 'translation')->findAll($criteria);
    }
  }

  private static function create_word_mp3($text) {
    $audio_path = realpath("./audio");

    if(!file_exists($audio_path . "/$text.mp3")) {
      $tts = new TextToSpeech($text);
      $result = $tts->saveToFile($audio_path . "/$text.mp3");
    }
  }
}
