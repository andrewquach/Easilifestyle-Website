<?php

class ProductImageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$old = $this->loadModel($id)->image;
			
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
			// delete file
			@unlink(Yii::getPathOfAlias('webroot').'/assets/products/'.$old);

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
	public function actionIndex($product)
	{
		if(isset($_POST['ProductImage']))
		{
			$model=new ProductImage();
			$model->setAttributes($_POST['ProductImage'], false);
			$model->product_id = $product;
			$img = CUploadedFile::getInstance($model,'image');
			if($model->save()) {
				// save image
				if ($img) {
					$fn = basename($img->getName());
					$model->image = $model->id.$fn;
					$img->saveAs(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->image);
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->image);
					$image->resize(Yii::app()->params['productwidth'], Yii::app()->params['productheight'], Image::WIDTH);
					$image->save();
					
					$model->saveAttributes(array('image'));
				}
				$this->redirect(array('index','product'=>$product));
			}
		}
		
		$model=new ProductImage('search');
		$model->unsetAttributes();  // clear any default values
		$model->product_id = $product;
		
		$this->render('index',array(
			'model'=>$model,
			'product'=>Product::model()->findByPk((int)$product),
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

		if(isset($_POST['ProductImage']))
		{
			$old_img = $model->image;
			$model->setAttributes($_POST['ProductImage'], false);		
			$img = CUploadedFile::getInstance($model,'image');
			
			// save image
			if ($img) {
				$fn = basename($img->getName());
				$model->image = $model->id.$fn;
				$img->saveAs(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->image);
				
				$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->image);
				$image->resize(Yii::app()->params['productwidth'], Yii::app()->params['productheight'], Image::WIDTH);
				if ($image->save()) {
					if ($old_img != $model->image)
						@unlink(Yii::getPathOfAlias('webroot').'/assets/products/'.$old_img);
				}
				else {
					$model->image = $old_img;
				}
				
				$model->saveAttributes(array('image'));
			}
			else {
				$model->image = $old_img;
			}
			
			if($model->save()) {				
				$this->redirect(array('index','product'=>$model->product_id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'product'=>Product::model()->findByPk((int)$model->product_id),
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ProductImage::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-image-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
