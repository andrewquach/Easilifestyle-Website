<?php

class ProductController extends Controller
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
		$model=new Product;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->setAttributes($_POST['Product'], false);
			if (strpos($model->attributes['url'], 'http://') === false) {
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->created = time();
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			if($model->save()) {
				// save image
				if ($thumb) {
					$fn = basename($thumb->getName());
					$model->thumbnail = 'thump_'.$model->id.$fn;
					$thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->thumbnail);
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->thumbnail);
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
	
	public function actionImport()
	{
		$model = new Product;
		$params = array('model' => $model);
		
		if(isset($_POST['Product']))
		{
			Yii::import('ext.phpexcelreader.JPhpExcelReader');
			$file = CUploadedFile::getInstance($model,'import');
			
			if ($file) {
				$excel = new JPhpExcelReader($file->getTempName());
				$errArr = array();
				
				if ($excel) {
					$count = 0;
					$line = 2;
					while ($name = $excel->val($line, 'B')) {
						$model = new Product;
						$model->name = $name;
						$model->description = $excel->val($line, 'C');
						$model->enabled = strtolower($excel->val($line, 'D')) == 'y';
						$model->price = $excel->val($line, 'E');
						$model->el_points = $excel->val($line, 'F');
						$model->bv_points = $excel->val($line, 'G');
						$model->url = $excel->val($line, 'J');
						$model->old_price = $excel->val($line, 'K');
						$model->non_html = $excel->val($line, 'L');
						
						// look for status
						$status_code = $excel->val($line, 'H');
						if (!empty($status_code)) {
							$status = ProductStatus::model()->findByAttributes(array('code' => $status_code));
							if (empty($status)) {
								$errArr[$line] = 'Status is invalid.';
								$line ++;
								continue;
							}
							$model->status_id = $status->id;
						}
						
						// look for category
						$cat_name = $excel->val($line, 'I');
						$cat = Category::model()->findByAttributes(array('name' => $cat_name));
						if (empty($cat)) {
							$errArr[$line] = 'Category not found.';
							$line ++;
							continue;
						}
						else if (is_array($cat)) {
							$errArr[$line] = 'Category name `'.$cat_name.'` is ambiguous.';
							$line ++;
							continue;
						}
						$model->category_id = $cat->id;
						
						if (!$model->save()) {
							$errors = $model->getErrors();
							$msgs = array();
							foreach ($errors as $err) {
								$msgs[] = implode("; ", $err);
							}
							$errArr[$line] = implode("; ", $msgs);
							$line ++;
							continue;
						}
						
						$count++;
						$line ++;
					}
					
					if ($line == 2 && empty($name)) {
						$model->addError('import', 'No record to import.');
					}
					else {
						$model = new Product;
						$params['total'] = $line - 2;
						$params['count'] = $count;
						$params['errors'] = $errArr;
					}
				}
				else {
					$model->addError('import', 'Failed to read from Excel file.');
				}
			}
			else {
				$model->addError('import', 'Please upload a file.');
			}
		}

		$this->render('import', $params);
	}
	
	public function actionDuplicate($id) {
		$model=$this->loadModel($id);
		$new = new Product;
		$new->setAttributes($model->attributes);
		$new->id = null;
		$new->created = time();
		
		// create new product
		if($new->save()) {
			
			// duplicate thumb
			if ($model->thumbnail) {
				$fn = substr($model->thumbnail, 6);
				$fn = substr($fn, strlen($id));
				$new->thumbnail = 'thump_'.$new->id.$fn;
				copy(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->thumbnail,
				 Yii::getPathOfAlias('webroot').'/assets/products/'.$new->thumbnail);
				 
				$new->saveAttributes(array('thumbnail'));
			}
			
			// dupliate images
			$images = ProductImage::model()->findAllByAttributes(array('product_id' => $model->id));
			foreach ($images as $img) {
				$image = new ProductImage;
				$image->setAttributes($img->attributes);
				$image->id = null;
				$image->product_id = $new->id;
				if ($image->save()) {
					$fn = substr($img->image, strlen($img->id));
					$image->image = $image->id.$fn;
					copy(Yii::getPathOfAlias('webroot').'/assets/products/'.$img->image,
					 Yii::getPathOfAlias('webroot').'/assets/products/'.$image->image);
					 
					$image->saveAttributes(array('image'));
				}
			}
			
			// dupliate reviews
			$reviews = ProductReview::model()->findAllByAttributes(array('product_id' => $model->id));
			foreach ($reviews as $rev) {
				$review = new ProductReview;
				$review->setAttributes($rev->attributes);
				$review->id = null;
				$review->product_id = $new->id;
				$review->save();
			}
			
			// dupliate promotions
			$promotions = ProductPromotion::model()->findAllByAttributes(array('product_id' => $model->id));
			foreach ($promotions as $pro) {
				$promotion = new ProductPromotion;
				$promotion->setAttributes($pro->attributes);
				$promotion->id = null;
				$promotion->product_id = $new->id;
				$promotion->save();
			}
			
			$this->redirect(array('view','id'=>$new->id));
		}
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
		$this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$old_thumb = $model->thumbnail;
			
			$model->setAttributes($_POST['Product'], false);
			if (strpos($model->attributes['url'], 'http://') === false) {
				$model->url = 'http://'.$model->attributes['url'];
			}
			$model->updated = time();
			$thumb = CUploadedFile::getInstance($model,'thumbnail');
			// save image
			if ($thumb) {
				$fn = basename($thumb->getName());
				$model->thumbnail = 'thump_'.$model->id.$fn;
				if ($thumb->saveAs(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->thumbnail)) {
					
					$image = Yii::app()->image->load(Yii::getPathOfAlias('webroot').'/assets/products/'.$model->thumbnail);
					$image->resize(Yii::app()->params['thumbwidth'], Yii::app()->params['thumbheight'], Image::WIDTH);
					if ($image->save()) {
						if ($old_thumb != $model->thumbnail)
							@unlink(Yii::getPathOfAlias('webroot').'/assets/products/'.$old_thumb);
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
				$this->redirect(array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionPositioning()
	{
		if(isset($_POST['orders'])) {
			$orders = explode(',', $_POST['orders']);
			foreach ($orders as $pos => $id) {
				$item=Product::model()->findByPk($id);
				$item->saveAttributes(array('position' => $pos));
			}
			
			$this->redirect(array('index'));
		}
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

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
		$model=Product::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
