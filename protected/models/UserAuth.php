<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserAuth implements IUserIdentity
{
  const ERROR_NONE=0;
	const ERROR_LOGIN_OR_EMAIL_INVALID=1;
	const ERROR_PASSWORD_INVALID=2;
	const ERROR_UNKNOWN_IDENTITY=100;
  
  private $state = array();
  
  public $errorCode = self::ERROR_UNKNOWN_IDENTITY;


  private $id;
  private $username;
  private $password;
  
  public function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }
  
  public function authenticate() {
    $user = user::model()->find("login=:login OR email=:email", array(':login' => $this->username, ':email' => $this->username));

    if($user) {
      if($user->password == md5($this->password)) {
        $this->id = $user->id;
        
        $this->errorCode = self::ERROR_NONE;
      } else {
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
      }
    } else {
      $this->errorCode = self::ERROR_LOGIN_OR_EMAIL_INVALID;
    }
    
    return $this->errorCode == self::ERROR_NONE;
  }

  public function getId() {
    return $this->id;
  }

  public function getIsAuthenticated() {
    return $this->errorCode==self::ERROR_NONE;
  }

  public function getName() {
    return $this->username;
  }
  
  /**
	 * Sets an array of persistent states.
	 *
	 * @param array $states the identity states that should be persisted.
	 */
	public function setPersistentStates($states)
	{
		$this->_state = $states;
	}

  public function getPersistentStates() {
    return $this->state;
  }
  
  	/**
	 * Gets the persisted state by the specified name.
	 * @param string $name the name of the state
	 * @param mixed $defaultValue the default value to be returned if the named state does not exist
	 * @return mixed the value of the named state
	 */
	public function getState($name,$defaultValue=null)
	{
		return isset($this->_state[$name])?$this->_state[$name]:$defaultValue;
	}

	/**
	 * Sets the named state with a given value.
	 * @param string $name the name of the state
	 * @param mixed $value the value of the named state
	 */
	public function setState($name,$value)
	{
		$this->_state[$name]=$value;
	}

	/**
	 * Removes the specified state.
	 * @param string $name the name of the state
	 */
	public function clearState($name)
	{
		unset($this->_state[$name]);
	}
}