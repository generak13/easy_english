<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
	public $login;
  public $email;
	public $password;
  public $repeat_password;

  private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login, email, password, repeat_password', 'required'),
//      array('login', 'unique', 'Login must be uniqe'),
//      array('email', 'unique', 'Email must be uniqe'),
			// password needs to be authenticated
			array('password, repeat_password', 'length', 'min'=>6, 'max'=>40),
      array('repeat_password', 'compare', 'compareAttribute' => 'password')
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserAuth($this->login, $this->email, $this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function register()
	{
    echo '<pre>';
    print_r('sad');
    die();
//		if($this->_identity===null)
//		{
//			$this->_identity=new UserAuth($this->login, $this->email, $this->password);
//			$this->_identity->authenticate();
//		}
//		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
//		{
//			$duration = 3600*24*30; // 30 days
//			Yii::app()->user->login($this->_identity,$duration);
//			return true;
//		}
//		else
//			return false;
	}
}
