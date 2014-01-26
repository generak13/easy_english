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
			array('user_id, word_id, translation_id, context, added_datetime', 'required'),
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
}