<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('Product/index'),
	$product->name=>array('Product/view','id'=>$product->id),
	'Reviews'
);
?>
<h1>Product Reviews</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-review-grid',
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
			'template' => '{update} {delete}'
		),
	),
)); ?>

<h2>Add Review</h2>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>