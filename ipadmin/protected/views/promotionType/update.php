<?php
$this->breadcrumbs=array(
	'Promotion Types'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PromotionType', 'url'=>array('index')),
	array('label'=>'Create PromotionType', 'url'=>array('create')),
	array('label'=>'View PromotionType', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PromotionType', 'url'=>array('admin')),
);
?>

<h1>Update PromotionType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>