<?php
$this->breadcrumbs=array(
	'Keys',
);

$this->menu=array(
	array('label'=>'Create Key', 'url'=>array('create')),
	array('label'=>'Manage Key', 'url'=>array('admin')),
);
?>

<h1>Keys</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
