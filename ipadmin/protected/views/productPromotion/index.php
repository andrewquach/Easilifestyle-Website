<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('Product/index'),
	$product->name=>array('Product/view','id'=>$product->id),
	'Promotions'
);
?>
<h1>Product Promotions</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-promotion-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'header' => 'S/N',
			'value' => '$row+1',
			'headerHtmlOptions' => array('width' => '30px'),
			'htmlOptions' => array('style' => 'text-align:center;')
		),
		array(
			'name' => 'image',
			'type' => 'html',
			'value' => '"<h3>".$data->title."</h3><p>".$data->content."</p>"',
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>

<h2>Add Promotion</h2>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>