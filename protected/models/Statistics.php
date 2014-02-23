<?php

/**
 * This is the model class for table "statistics".
 *
 * The followings are the available columns in table 'statistics':
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property integer $points
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Statistics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'statistics';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, date, points', 'required'),
            array('user_id, points', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, date, points', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'date' => 'Date',
			'points' => 'Points',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('points',$this->points);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Statistics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function saveUserStatisic($userId, $points) {
		$date = date('Y-m-d');
		
		$criteria = new CDbCriteria();
		$criteria->condition = "`user_id` =:user_id AND `date` = :date";
		$criteria->params = array(
			':user_id' => $userId,
			':date' => $date
		);
		
		$stat = Statistics::model()->find($criteria);
		
		if($stat) {
			$stat->points += $points;
		} else {
			$stat = new Statistics();
			$stat->user_id = $userId;
			$stat->date = $date;
			$stat->points = $points;
		}
		
		$stat->save();
		
		echo '<pre>';
		print_r($stat->getErrors());
		die();
	}
	
	public static function getUserWeekStatistics($userId) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('`user_id` = ' . $userId);
		$criteria->addBetweenCondition('date', date('Y-m-d', strtotime('-1 week')), date('Y-m-d'));
		
		$stats = Statistics::model()->findAll($criteria);
		
		$result = array();
		$data = array();
		
		foreach ($stats as $elem) {
			$data[$elem->date] = $elem->points;
		}
		
		$total_points = 0;
		
		for($i = 0; $i < 7; $i++) {
			$days_ago = 6 - $i;
			$date = date('Y-m-d', strtotime("-$days_ago days"));
			
			if(isset($data[$date])) {
				$total_points += $data[$date];
			}
			
			$result[] = array(
				'date' => $date,
				'points' => $total_points
			);
		}
		
		return $result;
	}
}
