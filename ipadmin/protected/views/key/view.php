<?php
$this->breadcrumbs=array(
	'Keys'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Key', 'url'=>array('index')),
	array('label'=>'Create Key', 'url'=>array('create')),
	array('label'=>'Update Key', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Key', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Key', 'url'=>array('admin')),
);
?>

<h1>View Key #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'key',
		'created',
		'enabled',
		'description',
	),
)); ?>
