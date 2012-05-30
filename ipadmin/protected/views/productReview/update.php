<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('Product/index'),
	$product->name=>array('Product/view','id'=>$product->id),
	'Reviews' => array('ProductReview/index', 'product'=>$product->id),
	'Update'
);
?>

<h1>Update Product Review</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>