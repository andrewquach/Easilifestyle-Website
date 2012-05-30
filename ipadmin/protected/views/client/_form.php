<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'client-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'entry_date'); ?>
		<?php echo $form->textField($model,'entry_date'); ?>
		<?php echo $form->error($model,'entry_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_connected'); ?>
		<?php echo $form->textField($model,'last_connected'); ?>
		<?php echo $form->error($model,'last_connected'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->