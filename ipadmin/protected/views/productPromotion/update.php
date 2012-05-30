<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('Product/index'),
	$product->name=>array('Product/view','id'=>$product->id),
	'Promotions' => array('ProductPromotion/index', 'product'=>$product->id),
	'Update'
);
?>

<h1>Update Product Promotion</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>