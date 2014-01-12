<?php

class MySiteController extends Controller
{
  
  public static $default_username = 'root';
  public static $default_password = 'monkey';
  
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
    $model=new AuthForm();

    // if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['AuthForm']))
		{
      
      $model->attributes=$_POST['AuthForm'];

      // validate user input and redirect to the previous page if valid
      if($model->validate() && $model->login())
        $this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
  
  public function actionRegister() {
    $model = new RegisterForm();
    
    // if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='register-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
    
    if(isset($_POST['RegisterForm'])) {
      $model->attributes = $_POST['RegisterForm'];
 
      if($model->validate()) {
        $user = user::model()->find("email=:email OR login=:login", array(':email' => $model->email, ':login' => $model->login));
        
        if($user) {
          if($user->email == $model->email) {
            $model->addError('email', 'User with this email is already exists');
          } else {
            $model->addError('login', 'User with this login is already exists');
          }
          
          $this->render('register', array('model' => $model));
          Yii::app()->end();
        }

        $user = new user();
        $user->email = $model->email;
        $user->login = $model->login;
        $user->password = md5($model->password);
        $user->register = date("Y-m-d");
        $user->save();
        
        
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    
    $this->render('register', array('model' => $model));
  }

  /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
  
  public function actionTest() {
    $this->render('test');
  }
}