<?php

class content extends CActiveRecord
{
  public static $TYPE_TEXT = 1;
  public static $TYPE_VIDEO = 2;
  public static $TYPE_AUDIO = 3;
  
  public function rules() {
    return array(
      array('id, title, type', 'safe', 'on' => 'search')
    );
  }
  
  public static function model($className=__CLASS__)
  {
      return parent::model($className);
  }

  public function tableName()
  {
      return 'content';
  }
  
  /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user2content' => array(self::HAS_MANY, 'User2content', 'content_id'),
		);
	}

  
  public function search() {
    $criteria = new CDbCriteria();

    $criteria->compare('id', $this->id);
    $criteria->compare('title', $this->title, true);
    $criteria->compare('type', $this->type);
    
    $dp = new CActiveDataProvider($this, array(
      'criteria' => $criteria,
      'sort' => array(
        'defaultOrder' => 'date DESC',  // this is it.
         'attributes'=>array(
            'title', 'date'
        ),
      ),
      'pagination' => array(
        'pageSize' => 1,
      ),
    ));
    
    return $dp;
  }
  
}