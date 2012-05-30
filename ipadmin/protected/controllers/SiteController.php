<?php

class SiteController extends Controller
{
	public function filters() {
		$filters = parent::filters();
		return array_merge($filters, array(
			'login + login',
		));
	}
	
	public function filterLogin($filterChain) {
    	if (!Yii::app()->user->isGuest) {
			$this->redirect(array('/site/index'));
		}
		$filterChain->run();
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
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
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;
		// collect user input data
		
		if(isset($_POST['LoginForm']))
		{
			$model->username = $_POST['LoginForm']['username'];
			$model->password = $_POST['LoginForm']['password'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$this->redirect(array('/site/index'));
			}
		}
		
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionAbout_us() {
		$path = Yii::getPathOfAlias('webroot.protected.views.site');
		$path = $path.'/aboutus.html';
		$saved = 0;
		
		if (!empty($_POST) && isset($_POST['aboutus'])) {
			file_put_contents($path, $_POST['aboutus']);
			$content = $_POST['aboutus'];
			$saved = 1;
		}
		else {
			$content = file_get_contents($path);
		}
		$this->render('aboutus', array('file_content'=>$content, 'saved'=>$saved));
	}
	
	public function actionApp_intro() {
		$path = Yii::getPathOfAlias('webroot.protected.views.site');
		$path = $path.'/appintro.html';
		$saved = 0;
		
		if (!empty($_POST) && isset($_POST['appintro'])) {
			file_put_contents($path, $_POST['appintro']);
			$content = $_POST['appintro'];
			$saved = 1;
		}
		else {
			$content = file_get_contents($path);
		}
		$this->render('appintro', array('file_content'=>$content, 'saved'=>$saved));
	}
}