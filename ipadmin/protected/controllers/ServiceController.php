<?php
class ServiceController extends CController {
	public $layout='//layouts/service';
	
	public function actionPromotion_list($start, $limit) {
		$args = func_get_args();
		$criteria = new CDbCriteria;
		
		$criteria->params = array(':today' => date('Y-m-d'), ':lastday' => date('Y-m-d', strtotime('-1 day')));
		$criteria->addColumnCondition(array('enabled' => 1));
		$criteria->addCondition('started <= :today');
		$criteria->addCondition('ended >= :lastday');
		
		$criteria->order = 'position ASC';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'title', 'short_description', 'description', 'started', 'ended', 'thumbnail');
		
		$model = Promotion::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'promotions/%s'),
			'select' => $criteria->select,
		));
	}
	
	public function actionPromotion($id) {
		$criteria = new CDbCriteria;
		$criteria->params = array(':today' => date('Y-m-d'), ':lastday' => date('Y-m-d', strtotime('-1 day')));
		$criteria->addColumnCondition(array('id' => $id));
		$criteria->addCondition('started <= :today');
		$criteria->addCondition('ended >= :lastday');
		
		$criteria->select = array('id', 'title', 'short_description', 'description', 'non_html', 'started', 'ended', 'thumbnail', 'image', 'url');
		
		$model = Promotion::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'promotions/%s',
				'image' => 'promotions/%s'),
			'select' => $criteria->select,
		));
	}
	
	public function actionNews_list($start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->params = array(':today' => time());
		$criteria->addColumnCondition(array('published' => 1));
		$criteria->addCondition('published_date <= :today');
		
		$criteria->order = 'position ASC';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'title', 'short_content', 'published_date', 'thumbnail');
		
		$model = Article::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'articles/%s'),
			'dates'=>array('published_date'),
			'select' => $criteria->select,
		));
	}
	
	public function actionNews($id) {
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array('id' => $id));
		$criteria->select = array('id', 'title', 'long_content', 'non_html', 'published_date', 'thumbnail', 'url');
		
		$model = Article::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'articles/%s'
			),
			'dates'=>array('published_date'),
			'select' => $criteria->select,
		));
	}
	
	public function actionCatalogue_list($start, $limit) {
		$criteria = new CDbCriteria;
		$criteria->alias = 'Category';
		
		$criteria->addColumnCondition(array('Category.enabled' => 1));
		
		$criteria->order = 'Category.position ASC';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'name', 'description');
		$criteria->with = 'activeChildCount';
		
		$model = Category::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'related'=>array('activeChildCount'),
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct_list($id, $start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->addColumnCondition(array('enabled' => 1));
		$criteria->addColumnCondition(array('category_id' => $id));
		
		$criteria->order = 'position ASC';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'name', 'description', 'thumbnail', 'price', 'el_points', 'bv_points', 'old_price');
		$criteria->with = 'status';
		
		$model = Product::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'products/%s'
			),
			'related'=>array('status.description', 'status.color'),
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct_search($id, $text, $start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->params = array(':text1' => '%'.$text.'%', ':text2' => '%'.$text.'%');
		$criteria->addColumnCondition(array('enabled' => 1));
		$criteria->addColumnCondition(array('category_id' => $id));
		$criteria->addCondition("(name like :text1 or t.description like :text2)");
		
		$criteria->order = 'name';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'name', 'description', 'thumbnail', 'price', 'el_points', 'bv_points', 'old_price');
		$criteria->with = 'status';
		
		$model = Product::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'products/%s'
			),
			'related'=>array('status.description', 'status.color'),
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct($id) {
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array('t.id' => $id));
		$criteria->select = array('id', 'name', 'description', 'non_html', 'thumbnail', 'price', 'el_points', 'bv_points', 'url', 'old_price');
		$criteria->with = 'status';
		
		$model = Product::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'assets'=>array(
				'thumbnail' => 'products/%s'
			),
			'related'=>array('status.description', 'status.color'),
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct_review_list($id, $start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->addColumnCondition(array('product_id' => $id));
		
		$criteria->order = 'title';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'title', 'content');
		
		$model = ProductReview::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct_promotion_list($id, $start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->addColumnCondition(array('product_id' => $id));
		
		$criteria->order = 'title';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'title', 'content');
		
		$model = ProductPromotion::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'select' => $criteria->select,
		));
	}
	
	public function actionProduct_image_list($id, $start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->addColumnCondition(array('product_id' => $id));
		
		$criteria->order = 'title';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'title', 'image');
		
		$model = ProductImage::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'select' => $criteria->select,
			'assets'=>array(
				'image' => 'products/%s'
			),
		));
	}
	
	public function actionStore_list($start, $limit) {
		$criteria = new CDbCriteria;
		
		$criteria->order = 'name';
		$criteria->offset = $start;
		$criteria->limit = $limit;
		$criteria->select = array('id', 'name', 'description', 'address', 'postal_code', 'longitude', 'latitude', 'phone');
		
		$model = Store::model()->findAll($criteria);
		$this->render('json',array(
			'model'=>$model,
			'select' => $criteria->select,
		));
	}
	
	public function actionRedeem($id, $udid) {
		
		if(isset($id) && isset($udid))
		{
			// find promotion
			$criteria = new CDbCriteria;
		
			$criteria->params = array(':today' => date('Y-m-d H:i:s'));
			$criteria->addColumnCondition(array('enabled' => 1));
			$criteria->addCondition('started <= :today');
			$criteria->addCondition('ended >= :today');
			$criteria->addCondition("id = " . $id);
			$criteria->limit = 1;
			$criteria->select = array('id', 'instruction');
			$model_p = Promotion::model()->findAll($criteria);
			if (empty($model_p)) {
				$this->render('error',array(
					'message'=>'Promotion not found'
				));
				return;
			}
			
			// check redemption
			$criteria = new CDbCriteria;
		
			$criteria->addColumnCondition(array('promotion_id' => $id));
			$criteria->addColumnCondition(array('client_id' => $udid));
			$c = Redemption::model()->count($criteria);
			if ($c > 0) {
				$this->render('error',array(
					'message'=>'This promotion has been redeemed.'
				));
				return;
			}
			
			$model=new Redemption;
			
			$model->promotion_id = $id;
			$model->created = time();
			$model->client_id = $udid;
			$model->save();
			$this->render('json',array(
				'model'=>$model_p,
				'select' => array('id', 'instruction'),
			));
		}
		else {
			$this->render('error',array(
				'message'=>'Promotion ID and UDID are required'
			));
		}
	}
	
	public function actionAbout_us() {
		$path = Yii::getPathOfAlias('webroot.protected.views.site').'/aboutus.html';
		$content = file_get_contents($path);
		$this->render('error',array(
			'message'=>$content,
		));
	}
	
	public function actionApp_intro() {
		$path = Yii::getPathOfAlias('webroot.protected.views.site').'/appintro.html';
		$content = file_get_contents($path);
		$this->render('error',array(
			'message'=>$content,
		));
	}
	
	private function error($message) {
		$this->render('error',array(
			'message'=>$message,
		));
	}
}
?>