<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'article-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_content'); ?>
		<?php 
		//echo $form->textArea($model,'short_content',array('rows'=>2, 'cols'=>50,'maxlength'=>2000));
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'short_content',
			"config" => array(
				"height"=>"100px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		)); 
		?>
		<?php echo $form->error($model,'short_content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'long_content'); ?>
		<?php 
		//echo $form->textArea($model,'long_content',array('rows'=>12, 'cols'=>50));
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'long_content',
			"config" => array(
				"height"=>"400px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		));  
		?>
		<?php echo $form->error($model,'long_content'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'non_html'); ?>
		<?php 
		echo $form->textArea($model,'non_html',array('rows'=>4, 'cols'=>50));
		?>
		<?php echo $form->error($model,'non_html'); ?>
	</div>
	
	<?php if (!$model->isNewRecord && !empty($model->thumbnail)) : ?>
	<div class="row">
		<?php echo $form->labelEx($model,'Current Thumbnail'); ?>
		<?php echo CHtml::image(Yii::app()->params['siteurl']."/assets/articles/".$model->thumbnail, "Thumbnail"); ?>
	</div>
	<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'thumbnail'); ?>
		<?php echo $form->fileField($model,'thumbnail',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'thumbnail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'published'); ?>
		<?php echo $form->checkbox($model,'published'); ?>
		<?php echo $form->error($model,'published'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->