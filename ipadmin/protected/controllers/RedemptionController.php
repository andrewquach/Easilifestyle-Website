<?php

class RedemptionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Redemption('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Redemption'])) {
			$model->promotion_id=$_GET['Redemption']['promotion_id'];
			$model->from=$_GET['Redemption']['from'];
			$model->to=$_GET['Redemption']['to'];
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionDel()
	{
		$model=new Redemption('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Redemption'])) {
			$model->promotion_id=$_GET['Redemption']['promotion_id'];
			$model->from=$_GET['Redemption']['from'];
			$model->to=$_GET['Redemption']['to'];
		}
			
		$criteria=new CDbCriteria;
		$criteria->compare('promotion_id',$model->promotion_id);
		if (!empty($model->from)) {
			$criteria->params[':from'] = CDateTimeParser::parse($model->from, 'dd/MM/yyyy');
			$criteria->addCondition('t.created >= :from');
		}
		if (!empty($model->to)) {
			$criteria->params[':to'] = CDateTimeParser::parse($model->to.' 23:59:59', 'dd/MM/yyyy hh:mm:ss');
			$criteria->addCondition('t.created <= :to');
		}
		
		Redemption::model()->deleteAll($criteria);
	}
	
	public function actionClear()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			Redemption::model()->deleteAll();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	
	public function actionExport() {
		$model=new Redemption('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Redemption'])) {
			$model->promotion_id=$_GET['Redemption']['promotion_id'];
			$model->from=$_GET['Redemption']['from'];
			$model->to=$_GET['Redemption']['to'];
		}
			
		$criteria=new CDbCriteria;
		$criteria->compare('promotion_id',$model->promotion_id);
		if (!empty($model->from)) {
			$criteria->params[':from'] = CDateTimeParser::parse($model->from, 'dd/MM/yyyy');
			$criteria->addCondition('t.created >= :from');
		}
		if (!empty($model->to)) {
			$criteria->params[':to'] = CDateTimeParser::parse($model->to.' 23:59:59', 'dd/MM/yyyy hh:mm:ss');
			$criteria->addCondition('t.created <= :to');
		}
		
		$model = Redemption::model()->findAll($criteria);
		if (!empty($model)) {
			Yii::import('application.extensions.phpexcel.JPhpExcel');
			
			$xls = new JPhpExcel('UTF-8', false, 'Redemption');
			
			$values = array(array('S/N', 'Date Time', 'Promotion', 'UDID'));
			foreach ($model as $no=>$v) {
				$row = array($no+1);
				$row[] = date('d/m/Y H:i', $v->created);
				$row[] = $v->promotion->title;
				$row[] = $v->client_id;
				$values[] = $row;
			}
			$xls->addArray($values);
			$xls->generateXML('redemption');
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Redemption::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='redemption-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
