<?php
$this->breadcrumbs=array(
	'Manage Stores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Stores', 'url'=>array('index')),
);
?>

<h1>Create Store</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>