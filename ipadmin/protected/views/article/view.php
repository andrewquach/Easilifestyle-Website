<?php
$this->breadcrumbs=array(
	'Articles'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create Article', 'url'=>array('create')),
	array('label'=>'Update Article', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Article', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Articles', 'url'=>array('index')),
);
?>

<h1>View Article</h1>

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
		'short_content:html',
		'long_content:html',
		'non_html',
		'created:datetime',
		'updated:datetime',
		array(
			'name'=>'thumbnail',
			'type'=>'html',
			'value'=>CHtml::image(Yii::app()->params['siteurl']."/assets/articles/".$model->thumbnail, "Thumbnail"),			
		),
		'published:boolean',
		'published_date:datetime',
	),
)); ?>
