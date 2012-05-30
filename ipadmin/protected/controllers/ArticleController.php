<?php

class ArticleController extends Controller
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
		$model=new Article;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Article']))
		{
			$model->attributes=$_POST['Article'];
			if (strpos($model->attributes['url'], 'http://') === false) {
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->published = $_POST['Article']['published'];
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			$model->created = time();
			if ($model->published) {
				$model->published_date = time();
			}
			else {
				$model->published_date = null;
			}
			if($model->save()) {
				// save image
				if ($thumb) {
					$fn = basename($thumb->getName());
					$model->thumbnail = $model->id.$fn;
					$thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/articles/'.$model->thumbnail);
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/articles/'.$model->thumbnail);
					$image->resize(Yii::app()->params['thumbwidth'], Yii::app()->params['thumbheight'], Image::WIDTH);
					$image->save();
					
					$model->saveAttributes(array('thumbnail'));
				}
				
				
				$this->redirect(array('view','id'=>$model->id));
			}
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

		if(isset($_POST['Article']))
		{
			$old_thumb = $model->thumbnail;
			
			$model->attributes=$_POST['Article'];
			if (strpos($model->attributes['url'], 'http://') === false) {
				var_dump("here");
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->published = $_POST['Article']['published'];
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			$model->updated = time();
			if ($model->published) {
				$model->published_date = time();
			}
			else {
				$model->published_date = null;
			}
			// save image
			if ($thumb) {
				$fn = basename($thumb->getName());
				$model->thumbnail = $model->id.$fn;
				if ($thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/articles/'.$model->thumbnail)) {
				
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/articles/'.$model->thumbnail);
					$image->resize(Yii::app()->params['thumbwidth'], Yii::app()->params['thumbheight'], Image::WIDTH);
					if ($image->save()) {
						if ($old_thumb != $model->thumbnail)
							@unlink(Yii::getPathOfAlias('webroot').'/assets/articles/'.$old_thumb);
					}
					else {
						$model->thumbnail = $old_thumb;
					}
				}
			}
			else {
				$model->thumbnail = $old_thumb;
			}
				
			if($model->save()) {
				$this->redirect(array('view','id'=>$model->id));
			}
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
		$model=new Article('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Article']))
			$model->attributes=$_GET['Article'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionPositioning()
	{
		if(isset($_POST['orders'])) {
			$orders = explode(',', $_POST['orders']);
			foreach ($orders as $pos => $id) {
				$article=Article::model()->findByPk($id);
				$article->saveAttributes(array('position' => $pos));
			}
			
			$this->redirect(array('index'));
		}
		$model=new Article('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Article']))
			$model->attributes=$_GET['Article'];

		$this->render('positioning',array(
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
		$model=Article::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='article-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
