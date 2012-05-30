<?php
$this->breadcrumbs=array(
	'Promotion Types',
);

$this->menu=array(
	array('label'=>'Create PromotionType', 'url'=>array('create')),
	array('label'=>'Manage PromotionType', 'url'=>array('admin')),
);
?>

<h1>Promotion Types</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
