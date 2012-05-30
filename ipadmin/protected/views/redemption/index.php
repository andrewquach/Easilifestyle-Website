<?php
Yii::app()->clientScript->registerScript('export', "
function export_click(url) {
	var data = $(this).serialize();
	window.open('', url+'?'+data);
	return false;
}
");

$this->breadcrumbs=array(
	'Redemption History'
);
$this->menu=array(
	array('label'=>'Export', 'url'=>'#', 'linkOptions'=>array('class' => 'export-label')),
	array('label'=>'Delete Current Records', 'url'=>'#', 'linkOptions'=>array('class' => 'delete-label', 'confirm'=>'Are you sure you want to delete ALL current items?')),
	array('label'=>'Delete All', 'url'=>'#', 'linkOptions'=>array('submit'=>array('clear'),'confirm'=>'Are you sure you want to delete ALL items?')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('redemption-grid', {
		data: $(this).serialize()
	});
	return false;
});
$('.export-label').click(function(){
	var params = $('.search-form form').serialize();
	window.open('".$this->createAbsoluteUrl('Redemption/export')."?'+params,'');
	return false;
});
$('.delete-label').click(function(){
	var params = $('.search-form form').serialize();
	window.location = '".$this->createAbsoluteUrl('Redemption/del')."?'+params;
});
");

?>
<h1>Redemption History</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'redemption-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'header' => 'S/N',
			'type' => 'raw',
			'value' => '$row+1',
			'headerHtmlOptions' => array('width' => '30px'),
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		array(
			'name' => 'promotion',
			'value' => '$data->promotion->title',
		),
		'created:datetime',
		array(
			'class'=>'CButtonColumn',
			'template' => '{delete}',
		),
	),
)); ?>
