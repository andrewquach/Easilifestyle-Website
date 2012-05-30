<?php
$this->breadcrumbs=array(
	'Manage Stores'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Store', 'url'=>array('create')),
	array('label'=>'View Store', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stores', 'url'=>array('index')),
);
?>

<h1>Update Store <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>