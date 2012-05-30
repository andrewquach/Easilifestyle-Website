<?php
$this->breadcrumbs=array(
	'Categories Positioning',
);

$this->menu=array(
	array('label'=>'Manage Categories', 'url'=>array('index')),
	array('label'=>'Create Category', 'url'=>array('create')),
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

<h1>Categories Positioning</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<br /><br />
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
));

$criteria=new CDbCriteria;
$criteria->order = 'position ASC';
		 
$this->widget('zii.widgets.jui.CJuiSortable', array(
	'id' => 'article-grid',
    'items'=>CHtml::listData($model->search()->getData(), 'id', 'name'),
    'itemTemplate'=>'<li id="{id}" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{content}</li>',
	'options'=>array(
		'update'=>"js:function(){
                       $('#orders').val($(this).sortable('toArray'));
                }",
	),
)); 

echo CHtml::hiddenField('orders', '', array('id' => 'orders'));
echo CHtml::submitButton('Save Changes');

$this->endWidget();
?>
