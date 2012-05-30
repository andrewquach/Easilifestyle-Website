<?php

class PromotionController extends Controller
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
		$model=new Promotion;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Promotion']))
		{
			$model->attributes=$_POST['Promotion'];
			if (strpos($model->attributes['url'], 'http://') === false) {
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->enabled = $_POST['Promotion']['enabled'];
			$model->started = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Promotion']['started'])));
			$model->ended = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Promotion']['ended'])));
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			$img = CUploadedFile::getInstance($model,'image');
			$model->created = time();
			
			if($model->save()) {
				$tosave = array();
				// save image
				if ($thumb) {
					$fn = basename($thumb->getName());
					$model->thumbnail = 'thump_'.$model->id.$fn;
					$thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->thumbnail);
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->thumbnail);
					$image->resize(Yii::app()->params['thumbwidth'], Yii::app()->params['thumbheight'], Image::WIDTH);
					$image->save();
					
					$tosave[] = 'thumbnail';
				}
				
				if ($img) {
					$fn = basename($img->getName());
					$model->image = $model->id.$fn;
					$img->saveAs(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->image);
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->image);
					$image->resize(Yii::app()->params['promotionwidth'], Yii::app()->params['promotionheight'], Image::WIDTH);
					$image->save();
					
					$tosave[] = 'image';
				}
				
				if (!empty($tosave))
					$model->saveAttributes($tosave);
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

		if(isset($_POST['Promotion']))
		{
			$old_thumb = $model->thumbnail;
			$old_img = $model->image;
			
			$model->attributes=$_POST['Promotion'];
			if (strpos($model->attributes['url'], 'http://') === false) {
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->enabled = $_POST['Promotion']['enabled'];
			$model->started = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Promotion']['started'])));
			$model->ended = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['Promotion']['ended'])));
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			$img = CUploadedFile::getInstance($model,'image');
			$model->updated = time();
			
			// save image
			if ($thumb) {
				$fn = basename($thumb->getName());
				$model->thumbnail = 'thump_'.$model->id.$fn;
				if ($thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->thumbnail)) {
				
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->thumbnail);
					$image->resize(Yii::app()->params['thumbwidth'], Yii::app()->params['thumbheight'], Image::WIDTH);
					if ($image->save()) {
						if ($old_thumb != $model->thumbnail)
							@unlink(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$old_thumb);
					}
					else {
						$model->thumbnail = $old_thumb;
					}
				}
			}
			else {
				$model->thumbnail = $old_thumb;
			}
			
			if ($img) {
				$fn = basename($img->getName());
				$model->image = $model->id.$fn;
				if ($img->saveAs(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->image)) {
				
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$model->image);
					$image->resize(Yii::app()->params['promotionwidth'], Yii::app()->params['promotionheight'], Image::WIDTH);
					if ($image->save()) {
						if ($old_img != $model->image)
							@unlink(Yii::getPathOfAlias('webroot').'/assets/promotions/'.$old_img);
					}
					else {
						$model->image = $old_img;
					}
				}
			}
			else {
				$model->image = $old_img;
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
		$model=new Promotion('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Promotion'])) {
			$model->attributes=$_GET['Promotion'];
			$model->from=$_GET['Promotion']['from'];
			$model->to=$_GET['Promotion']['to'];
			$model->status=$_GET['Promotion']['status'];
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionPositioning()
	{
		if(isset($_POST['orders'])) {
			$orders = explode(',', $_POST['orders']);
			foreach ($orders as $pos => $id) {
				$item=Promotion::model()->findByPk($id);
				$item->saveAttributes(array('position' => $pos));
			}
			
			$this->redirect(array('index'));
		}
		$model=new Promotion('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Promotion']))
			$model->attributes=$_GET['Promotion'];

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
		$model=Promotion::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='promotion-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
