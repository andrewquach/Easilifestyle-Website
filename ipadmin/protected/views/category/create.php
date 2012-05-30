<?php
$this->breadcrumbs=array(
	'Manage Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Categories', 'url'=>array('index')),
);
?>

<h1>Create Category</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>