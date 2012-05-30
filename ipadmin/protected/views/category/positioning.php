<?php
$this->breadcrumbs=array(
	'Categories Positioning',
);

$this->menu=array(
	array('label'=>'Manage Categories', 'url'=>array('index')),
	array('label'=>'Create Category', 'url'=>array('create')),
);
?>

<h1>Categories Positioning</h1>
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
));

$criteria=new CDbCriteria;
$criteria->order = 'position ASC';
		 
$this->widget('zii.widgets.jui.CJuiSortable', array(
	'id' => 'article-grid',
    'items'=>CHtml::listData($model->findAll($criteria), 'id', 'name'),
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
