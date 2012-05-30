<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-promotion-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php 
		//echo $form->textArea($model,'content',array('rows'=>3,'maxlength'=>500));
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'content',
			"config" => array(
				"height"=>"200px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		));  
		?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->