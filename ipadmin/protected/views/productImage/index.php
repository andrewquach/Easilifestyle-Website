<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('Product/index'),
	$product->name=>array('Product/view','id'=>$product->id),
	'Images'
);
?>
<h1>Product Images</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-image-grid',
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
			'value' => 'CHtml::image(Yii::app()->params["siteurl"]."/assets/products/".$data->image, $data->title)."<p>".$data->title."</p>"',
		),
		array(
			'class'=>'CButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>

<h2>Upload Image</h2>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>