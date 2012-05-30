<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $loginError;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
		// username and password are required
		//array('username, password', 'required'),
		array('username', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if (!$this->validate()) return;
		
		$identity=new UserIdentity($this->username,$this->password);
		if($identity->authenticate())
		{
			Yii::app()->user->login($identity);
			return true;
		}
		else {
			$this->addError('', $identity->errorMessage);
			return false;
		}
	}
}
