<?php

/**
 * This is the model class for table "transation".
 *
 * The followings are the available columns in table 'transation':
 * @property string $id
 * @property string $word_id
 * @property string $text
 *
 * The followings are the available model relations:
 * @property Dictionary[] $dictionaries
 */
class Transation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('word_id, text', 'required'),
			array('word_id, text', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, word_id, text', 'safe', 'on'=>'search'),
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
        'dictionaries' => array(self::HAS_MANY, 'Dictionary', 'translation_id'),
        'word' => array(self::BELONGS_TO, 'Word', 'word_id'),
    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'word_id' => 'Word',
			'text' => 'Text',
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
		$criteria->compare('word_id',$this->word_id,true);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Transation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getTotalRecords($text = null, $OnlyNotVerified = false) {
		$criteria = new CDbCriteria();
		
		if($text) {
			$criteria->addSearchCondition('text', $text);
		}
		
		if($OnlyNotVerified) {
			$criteria->addCondition('`verified_date` IS NULL');
		}
		
		return Transation::model()->count($criteria);
	}
	
	public static function getRecords($text = null, $OnlyNotVerified = true, $limit = 15, $offset = null) {
		$criteria = new CDbCriteria();
		
		if($text) {
			$criteria->addSearchCondition('text', $text);
		}
		
		if($OnlyNotVerified) {
			$criteria->addCondition('`verified_date` IS NULL');
		}
		
		if ($limit) {
			$criteria->limit = $limit;
			$criteria->together = true;
		}

		if ($offset) {
			$criteria->offset = $offset;
		}

		return Transation::model()->findAll($criteria);
	}
}
