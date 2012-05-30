<?php
$this->breadcrumbs=array(
	'Manage Promotions'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create Promotion', 'url'=>array('create')),
	array('label'=>'Update Promotion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Promotion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Promotion', 'url'=>array('index')),
);
?>

<h1>View Promotion</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		array(
			'name'=>'url',
			'type'=>'html',
			'value'=>CHtml::link($model->url, $model->url, array('target' => '_blank')),			
		),
		'short_description:html',
		'description:html',
		'non_html',
		'instruction:html',
		array(
			'name'=>'started',
			'type'=>'date',
			'value'=>strtotime($model->started),
		),
		array(
			'name'=>'ended',
			'type'=>'date',
			'value'=>strtotime($model->ended),
		),
		'created:datetime',
		'updated:datetime',
		'enabled:boolean',
		array(
			'name'=>'thumbnail',
			'type'=>'html',
			'value'=>CHtml::image(Yii::app()->params['siteurl']."/assets/promotions/".$model->thumbnail, "Thumbnail"),			
		),
		array(
			'name'=>'image',
			'type'=>'html',
			'value'=>CHtml::image(Yii::app()->params['siteurl']."/assets/promotions/".$model->image, "Image"),			
		),
	),
)); ?>
