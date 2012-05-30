<?php
$this->breadcrumbs=array(
	'Articles Positioning',
);

$this->menu=array(
	array('label'=>'Manage Articles', 'url'=>array('index')),
	array('label'=>'Create Article', 'url'=>array('create')),
);
?>

<h1>Articles Positioning</h1>
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'article-form',
));

$criteria=new CDbCriteria;
$criteria->order = 'position ASC';
		 
$this->widget('zii.widgets.jui.CJuiSortable', array(
	'id' => 'article-grid',
    'items'=>CHtml::listData($model->findAll($criteria), 'id', 'title'),
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
