<?php
$this->breadcrumbs=array(
	'Promotions Positioning',
);

$this->menu=array(
	array('label'=>'Manage Promotions', 'url'=>array('index')),
	array('label'=>'Create Promotion', 'url'=>array('create')),
);
?>

<h1>Promotions Positioning</h1>
<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'promotion-form',
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
