<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property integer $id
 * @property integer $owner_id
 * @property string $title
 * @property integer $type
 * @property integer $genre
 * @property string $text
 * @property integer $lvl
 * @property integer $pages
 * @property string $player_link
 * @property string $date
 *
 * The followings are the available model relations:
 * @property Dictionary[] $dictionaries
 * @property User2content[] $user2contents
 */
class Content extends CActiveRecord
{
  
  public static $TYPE_TEXT = 1;
  public static $TYPE_VIDEO = 2;
  public static $TYPE_AUDIO = 3;
	
	public static $LVL_EASY = 1;
	public static $LVL_MEDUIM = 2;
	public static $LVL_HARD = 3;
  
  public static $WORDS_PER_PAGE = 300;
  public static $VIDEO_AUDIO_SPP = 1000;
  public static $TEXT_SPP = 3000;
	private static $CONTEXT_DELIMITERS = array('.', '?', '!', ';', "\n");
	
	private static function getTypeMapping() {
		return array(
			Content::$TYPE_TEXT => 'text',
			Content::$TYPE_AUDIO => 'audio',
			Content::$TYPE_VIDEO => 'video'
		);
	}
	
	private static function getLevelMapping() { 
		return array(
			Content::$LVL_EASY => 'easy',
			Content::$LVL_MEDUIM => 'medium',
			Content::$LVL_HARD => 'hard'
		);
	}
	
	public function getTypeByText($text) {
		$type = Content::$TYPE_TEXT;
        
		switch ($text) {
			case 'text':
				$type = Content::$TYPE_TEXT;
				break;
			case 'video':
				$type = Content::$TYPE_VIDEO;
				break;
			case 'audio':
				$type = Content::$TYPE_AUDIO;
				break;
			}
			
		return $type;		
	}
	
	public function getLevelByText($text) {
		$lvl = self::$LVL_EASY;
        
		switch ($text) {
			case 'easy':
				$lvl = self::$LVL_EASY;
				break;
			case 'medium':
				$lvl = self::$LVL_MEDUIM;
				break;
			case 'hard':
				$lvl = self::$LVL_HARD;
				break;
		}
		
		return $lvl;
	}
	
	public static function getTextType($type) {
		$mapping = Content::getTypeMapping();
		
		if(isset($mapping[$type])) {
			return $mapping[$type];
		}
		
		return false;
	}
	
	public static function getTextLevel($lvl) {
		$mapping = Content::getLevelMapping();
		
		if(isset($mapping[$lvl])) {
			return $mapping[$lvl];
		}
		
		return false;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('owner_id, title, type, genre, text, lvl, pages', 'required'),
			array('owner_id, type, genre, lvl, pages', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('player_link, date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, owner_id, title, type, genre, text, lvl, pages, player_link, date', 'safe', 'on'=>'search'),
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
			'dictionaries' => array(self::HAS_MANY, 'Dictionary', 'content_id'),
			'user2contents' => array(self::HAS_MANY, 'User2content', 'content_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'owner_id' => 'Owner',
			'title' => Yii::t('contents', 'Title'),
			'type' => Yii::t('contents', 'Type'),
			'genre' => Yii::t('contents', 'Genre'),
			'text' => Yii::t('contents', 'Text'),
			'lvl' => Yii::t('contents', 'Level'),
			'pages' => 'Pages',
			'player_link' => Yii::t('contents', 'Player Link'),
			'date' => 'Date',
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
		$criteria->compare('owner_id',$this->owner_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('genre',$this->genre);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('lvl',$this->lvl);
		$criteria->compare('pages',$this->pages);
		$criteria->compare('player_link',$this->player_link,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function calculatePagesCount($content) {
		if($content->type == self::$TYPE_TEXT) {
			return ceil(mb_strlen($content->text) / self::$TEXT_SPP);
		}
		
		return ceil(mb_strlen($content->text) / self::$VIDEO_AUDIO_SPP);
	}

	public function getContexts($string) {
		$return_array = Array($string); // The array to return
		$d_count = 0;
		while (isset(self::$CONTEXT_DELIMITERS[$d_count])) {
			$new_return_array = Array();
			foreach($return_array as $el_to_split) {
				$put_in_new_return_array = explode(self::$CONTEXT_DELIMITERS[$d_count],$el_to_split);
				foreach($put_in_new_return_array as $substr) {
					$new_return_array[] = $substr;
				}
			}
			
			$return_array = $new_return_array; // Replace the previous return array by the next version        
			$d_count++;
		}
		
		return $return_array; // Return the exploded elements}
	}
	
	public function getTextByPage($page, $start = 0) {
		$text = $this->text;
		$symbolsPerPage = $this->type == self::$TYPE_TEXT ? self::$TEXT_SPP : self::$VIDEO_AUDIO_SPP;
		$totalLength = mb_strlen($text);
		
		$start = $this->getPagePosition(mb_substr($text, 0, $symbolsPerPage * ($page - 1)));
		$end = $totalLength;
		
		if($totalLength > $page * $symbolsPerPage) {
			$end = $this->getPagePosition(mb_substr($text, 0, $symbolsPerPage * $page));
		}
		
		return mb_substr($text, $start, $end - $start);
	}
	
	private function getPagePosition($text) {
		$value = 0;

		foreach (self::$CONTEXT_DELIMITERS as $symbol) {
			$occurance = strrpos($text, $symbol);
			
			if($occurance > $value) {
				$value = $occurance;
			}
		}
		
		return $value;
	}
}
