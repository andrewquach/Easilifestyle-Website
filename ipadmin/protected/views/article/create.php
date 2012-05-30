<?php
$this->breadcrumbs=array(
	'Manage Articles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Articles', 'url'=>array('index')),
);
?>

<h1>Create Article</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>