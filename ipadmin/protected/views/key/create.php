<?php
$this->breadcrumbs=array(
	'Keys'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Key', 'url'=>array('index')),
	array('label'=>'Manage Key', 'url'=>array('admin')),
);
?>

<h1>Create Key</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>