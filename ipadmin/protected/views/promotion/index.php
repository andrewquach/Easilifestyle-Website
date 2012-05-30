<?php
$this->breadcrumbs=array(
	'Manage Promotions',
);

$this->menu=array(
	array('label'=>'Positioning', 'url'=>array('positioning')),
	array('label'=>'Create Promotion', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('promotion-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Promotions</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'promotion-grid',
	'enablePagination'=>true,
	'enableSorting'=>false,
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'header' => 'S/N',
			'type' => 'raw',
			'value' => '$row+1',
			'headerHtmlOptions' => array('width' => '30px'),
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		'title',
		array(
			'name'=>'started',
			'type'=>'date',
			'value'=>'strtotime($data->started)',
		),
		array(
			'name'=>'ended',
			'type'=>'date',
			'value'=>'strtotime($data->ended)',
		),
		'enabled:boolean',
		array(
			'header' => 'Ended',
			'type' => 'boolean',
			'value' => 'strtotime($data->ended) <= time()',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
