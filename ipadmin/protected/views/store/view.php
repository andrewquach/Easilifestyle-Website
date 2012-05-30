<?php
$this->breadcrumbs=array(
	'Manage Stores'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Store', 'url'=>array('create')),
	array('label'=>'Update Store', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Store', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Stores', 'url'=>array('index')),
);
?>

<h1>View Store</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
		'address',
		'postal_code',
		'longitude',
		'latitude',
		'phone',
		'fax',
		'email',
		array(
			'name'=>'map',
			'type'=>'html',
			'value'=> $this->widget("application.components.widgets.WGoogleStaticMap",array(
			    "center"=>$model->latitude.','.$model->longitude, // Or you can use text eg. Dundee, Scotland
			    "zoom"=>15, // Google map zoom level
			    "width"=>250, // image width
			    "height"=>250, // image Height
			    "markers"=>array(
			        array(
			            "style"=>array("color"=>"green"),
			            "locations"=>array($model->latitude.','.$model->longitude),
			        ),
			    ),
			), true),
		),
		'created:datetime',
		'updated:datetime',
	),
)); ?>