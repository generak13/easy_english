<?php

class CreateContentForm extends CFormModel
{
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
      array('player_link', 'safe')
		);
	}
}
