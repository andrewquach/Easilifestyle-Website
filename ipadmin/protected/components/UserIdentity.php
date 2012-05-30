<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ERROR_NOT_ACTIVATED = 3;
	
	private $_id;
	
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$record = User::model()->findByAttributes(array('username'=>$this->username));
		if($record === null){
			$this->errorMessage="Invalid username or password";
		}
		else if($record->password != '' && $record->password != md5($this->password)){
			$this->errorMessage="Invalid username or password";
		}
		else if ($record->enabled != 1) {
			$this->errorMessage="Your account is inactive";
		}
		else
		{
			$this->_id = $record->id;
			$this->setState('username', $record->username);
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
	}
	
	public function getId()
    {
        return $this->_id;
    }
}