<?php
$this->breadcrumbs=array(
	'Manage Products',
);

$this->menu=array(
	array('label'=>'Positioning', 'url'=>array('positioning')),
	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'Import Product', 'url'=>array('import')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('product-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Products</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-grid',
	'dataProvider'=>$model->search(),
	'enableSorting' => false,
	'columns'=>array(
		array(
			'header' => 'S/N',
			'type' => 'raw',
			'value' => '$row+1',
			'headerHtmlOptions' => array('width' => '30px'),
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		'name',
		'enabled:boolean',
		'price',
		'old_price',
		'el_points',
		'bv_points',
		array(
			'name' => 'category',
			'value' => '$data->category->name',
		),
		array(
			'name' => 'status',
			'value' => '$data->status->description',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
