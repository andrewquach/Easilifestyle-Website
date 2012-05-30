<?php
$this->breadcrumbs=array(
	'Manage Promotions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Promotion', 'url'=>array('index')),
);
?>

<h1>Create Promotion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>