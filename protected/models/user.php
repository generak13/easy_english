<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $login
 * @property string $email
 * @property integer $type
 * @property string $register
 * @property string $password
 * @property string $api_key
 *
 * The followings are the available model relations:
 * @property Statistics[] $statistics
 * @property User2content[] $user2contents
 */
class user extends CActiveRecord
{
	
	const USER_ADMINISTRATOR = 2;
	const USER_MODERATOR = 1;
	const USER_COMMON = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, email, type, register, password, api_key', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('login, email, password, api_key', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, email, type, register, password, api_key', 'safe', 'on'=>'search'),
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
			'statistics' => array(self::HAS_MANY, 'Statistics', 'user_id'),
			'user2contents' => array(self::HAS_MANY, 'User2content', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'email' => 'Email',
			'type' => 'Type',
			'register' => 'Register',
			'password' => 'Password',
			'api_key' => 'Api Key',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('register',$this->register,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('api_key',$this->api_key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return user the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getTotalRecords($text) {
		if (isset($text) && $text) {
			$criteria = new CDbCriteria();
			$criteria->addSearchCondition('login', $text, true, 'OR');
			$criteria->addSearchCondition('email', $text, true, 'OR');

			return user::model()->count($criteria);
		} else {
			return user::model()->count();
		}
	}

	public static function getRecords($text, $limit = null, $offset = null) {
		$criteria = new CDbCriteria();
		
		if(isset($text) && $text) {
			$criteria->addSearchCondition('login', $text, true, 'OR');
			$criteria->addSearchCondition('email', $text, true, 'OR');
		}
		
		if ($limit) {
			$criteria->limit = $limit;
			$criteria->together = true;
		}

		if ($offset) {
			$criteria->offset = $offset;
		}

		return user::model()->findAll($criteria);
	}
	
	public static function isUserExists($loginOrEmail) {
		$criteria = new CDbCriteria();
		$criteria->addCondition('`login` = :login', 'OR');
		$criteria->addCondition('`email` = :email', 'OR');
		$criteria->params = array(
			':login' => $loginOrEmail,
			':email' => $loginOrEmail
		);
		
		return user::model()->count($criteria) > 0;
	}
}
