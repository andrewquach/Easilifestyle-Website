<?php
$this->breadcrumbs=array(
	'Promotion Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PromotionType', 'url'=>array('index')),
	array('label'=>'Manage PromotionType', 'url'=>array('admin')),
);
?>

<h1>Create PromotionType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>