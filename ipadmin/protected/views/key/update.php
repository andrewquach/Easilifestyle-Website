<?php
$this->breadcrumbs=array(
	'Keys'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Key', 'url'=>array('index')),
	array('label'=>'Create Key', 'url'=>array('create')),
	array('label'=>'View Key', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Key', 'url'=>array('admin')),
);
?>

<h1>Update Key <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>