<?php

class StoreController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Store;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Store']))
		{
			$model->attributes=$_POST['Store'];
			$model->phone = $_POST['Store']['phone'];
			$model->fax = $_POST['Store']['fax'];
			$model->created = time();
			
			// get map location
			Yii::import('application.extensions.yiigeo.GeoCoder');
			$geocode = new GeoCoder;
			$geocode->init();
			$geocode->setApiKey(Yii::app()->params['gmapapikey']); // As of 1.0.2
			$geocode->setDriver('google'); // 'google' or 'yahoo'
			
			try
			{
				$result = $geocode->query($model->address.', Singapore '.$model->postal_code);
				$model->longitude = $result->longitude;
				$model->latitude = $result->latitude;
			}
			catch (GeoCode_Exception $ex)
			{
				$model->addError('address', 'Cannot get location for given address.');
			}
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Store']))
		{
			$model->attributes=$_POST['Store'];
			$model->phone = $_POST['Store']['phone'];
			$model->fax = $_POST['Store']['fax'];
			$model->updated = time();
			
			// get map location
			Yii::import('application.extensions.yiigeo.GeoCoder');
			$geocode = new GeoCoder;
			$geocode->init();
			$geocode->setApiKey(Yii::app()->params['gmapapikey']); // As of 1.0.2
			$geocode->setDriver('google'); // 'google' or 'yahoo'
			
			try
			{
				$result = $geocode->query($model->address.', Singapore '.$model->postal_code);
				$model->longitude = $result->longitude;
				$model->latitude = $result->latitude;
			}
			catch (GeoCode_Exception $ex)
			{
				$model->addError('address', 'Cannot get location for given address.');
			}

			if(!$model->hasErrors() && $model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Store('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Store']))
			$model->attributes=$_GET['Store'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Store::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='store-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
