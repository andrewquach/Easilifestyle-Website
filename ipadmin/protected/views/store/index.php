<?php
$this->breadcrumbs=array(
	'Manage Stores',
);

$this->menu=array(
	array('label'=>'Create Store', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('store-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Stores</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'store-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'header' => 'S/N',
			'type' => 'raw',
			'value' => '$row+1',
			'headerHtmlOptions' => array('width' => '30px'),
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		'name',
		'address',
		'created:date',
		'updated:date',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
