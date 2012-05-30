<?php
$this->breadcrumbs=array(
	'Manage Promotions'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Promotion', 'url'=>array('create')),
	array('label'=>'View Promotion', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Promotion', 'url'=>array('index')),
);
?>

<h1>Update Promotion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>