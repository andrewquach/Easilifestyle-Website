<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'Duplicate Product', 'url'=>array('duplicate', 'id'=>$model->id)),
	array('label'=>'Update Product', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Product', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Images', 'url'=>array('ProductImage/index', 'product'=>$model->id)),
	array('label'=>'Reviews', 'url'=>array('ProductReview/index', 'product'=>$model->id)),
	array('label'=>'Promotions', 'url'=>array('ProductPromotion/index', 'product'=>$model->id)),
	array('label'=>'Manage Products', 'url'=>array('index')),
);
?>

<h1>View Product</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
			'name'=>'url',
			'type'=>'html',
			'value'=>CHtml::link($model->url, $model->url, array('target' => '_blank')),			
		),
		'description:html',
		'non_html',
		'created:datetime',
		'updated:datetime',
		'enabled:boolean',
		array(
			'name'=>'thumbnail',
			'type'=>'html',
			'value'=>CHtml::image(Yii::app()->params['siteurl']."/assets/products/".$model->thumbnail, "Thumbnail"),			
		),
		'price',
		'old_price',
		'el_points',
		'bv_points',
		array(
			'name'=>'status',
			'value'=>$model->status->description,
		),
		array(
			'name'=>'category',
			'value'=>$model->category->name,
		),
	),
)); ?>
