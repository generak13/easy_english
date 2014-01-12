<?php

/**
 * This is the model class for table "exercise2dictionary".
 *
 * The followings are the available columns in table 'exercise2dictionary':
 * @property string $id
 * @property string $dictionary_id
 * @property string $exercise_id
 * @property string $status
 * @property string $last_learned_date
 *
 * The followings are the available model relations:
 * @property Exercise $exercise
 * @property Dictionary $dictionary
 */
class Exercise2dictionary extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'exercise2dictionary';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dictionary_id, status, last_learned_date', 'required'),
			array('dictionary_id, exercise_id, status', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dictionary_id, exercise_id, status, last_learned_date', 'safe', 'on'=>'search'),
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
			'exercise' => array(self::BELONGS_TO, 'Exercise', 'exercise_id'),
			'dictionary' => array(self::BELONGS_TO, 'Dictionary', 'dictionary_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dictionary_id' => 'Dictionary',
			'exercise_id' => 'Exercise',
			'status' => 'Status',
			'last_learned_date' => 'Last Learned Date',
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
		$criteria->compare('dictionary_id',$this->dictionary_id,true);
		$criteria->compare('exercise_id',$this->exercise_id,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('last_learned_date',$this->last_learned_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Exercise2dictionary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
