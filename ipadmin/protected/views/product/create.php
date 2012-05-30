<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Products', 'url'=>array('index')),
);
?>

<h1>Create Product</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>