<?php
$this->breadcrumbs=array(
	'Manage Products'=>array('index'),
	'Import',
);

$this->menu=array(
	array('label'=>'Manage Products', 'url'=>array('index')),
);
?>

<h1>Import Product</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'Excel File'); ?>
		<?php echo $form->fileField($model,'import',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo CHtml::link("Download Excel template", Yii::app()->params['siteurl']."/assets/product_template.xls") ?>
	</div>

	<?php if (!empty($count)): ?>
	<div class="row">
		<span class="note">Successfully imported <?php echo $count?>/<?php echo $total?> record(s).</span>
	</div>
	<?php endif; ?>
	
	<?php if (!empty($errors)) : ?>
	<div class="row">
		<ul>
			<?php foreach ($errors as $line => $msg) :?>
			<li class="errorMessage">Error at line [<?php echo $line?>]: <?php echo $msg?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Upload'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->