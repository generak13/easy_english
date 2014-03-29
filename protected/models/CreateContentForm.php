<?php

class CreateContentForm extends CFormModel
{
	public $identifier;
	public $title;
	public $genre;
	public $text;
	public $type;
	public $player_link;
	public $lvl;

	public function rules()
	{
		return array(
			array('title, text, genre, type, lvl', 'required'),
      array('identifier, player_link', 'safe')
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'title' => Yii::t('contents', 'Title'),
			'type' => Yii::t('contents', 'Type'),
			'genre' => Yii::t('contents', 'Genre'),
			'text' => Yii::t('contents', 'Text'),
			'lvl' => Yii::t('contents', 'Level'),
			'player_link' => Yii::t('contents', 'Player Link'),
		);
	}
}
