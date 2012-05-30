<?php
$this->breadcrumbs=array(
	'Manage Articles'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Article', 'url'=>array('create')),
	array('label'=>'View Article', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Articles', 'url'=>array('index')),
);
?>

<h1>Update Article</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>