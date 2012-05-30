<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'promotion-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
)); 

if (!$model->isNewRecord) {
	$model->started = date(Yii::app()->format->dateFormat, strtotime($model->started));
	$model->ended = date(Yii::app()->format->dateFormat, strtotime($model->ended));
}

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
		<?php echo $form->labelEx($model,'short_description'); ?>
		<?php 
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'short_description',
			"config" => array(
				"height"=>"200px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		)); 
		?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php 
		//echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); 
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'description',
			"config" => array(
				"height"=>"400px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		)); 
		?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'non_html'); ?>
		<?php 
		echo $form->textArea($model,'non_html',array('rows'=>4, 'cols'=>50));
		?>
		<?php echo $form->error($model,'non_html'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'instruction'); ?>
		<?php 
		//echo $form->textArea($model,'instruction',array('rows'=>6, 'cols'=>50));
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"model"=>$model,
			"attribute"=>'instruction',
			"config" => array(
				"height"=>"200px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		));  
		?>
		<?php echo $form->error($model,'instruction'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'started'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'attribute'=>'started',
			'model'=>$model,
		    // additional javascript options for the date picker plugin
		    'options'=>array(
		        'showAnim'=>'fold',
				'dateFormat'=>Yii::app()->params['JSDateFormat'],
		    ),
		    'htmlOptions'=>array(
		        'style'=>'height:20px;'
		    ),
		));
		?>
		<?php echo $form->error($model,'started'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ended'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'attribute'=>'ended',
			'model'=>$model,
		    // additional javascript options for the date picker plugin
		    'options'=>array(
		        'showAnim'=>'fold',
				'dateFormat'=>Yii::app()->params['JSDateFormat'],
		    ),
		    'htmlOptions'=>array(
		        'style'=>'height:20px;'
		    ),
		));
		?>
		<?php echo $form->error($model,'ended'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->checkBox($model,'enabled', array('value'=>1, 'uncheckedValue'=>0)); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<?php if (!$model->isNewRecord && !empty($model->thumbnail)) : ?>
	<div class="row">
		<?php echo $form->labelEx($model,'current_thumbnail'); ?>
		<?php echo CHtml::image(Yii::app()->params['siteurl']."/assets/promotions/".$model->thumbnail, "Thumbnail"); ?>
	</div>
	<?php endif; ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'thumbnail'); ?>
		<?php echo $form->fileField($model,'thumbnail',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'thumbnail'); ?>
	</div>

	<?php if (!$model->isNewRecord && !empty($model->image)) : ?>
	<div class="row">
		<?php echo $form->labelEx($model,'current_image'); ?>
		<?php echo CHtml::image(Yii::app()->params['siteurl']."/assets/promotions/".$model->image, "Image"); ?>
	</div>
	<?php endif; ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->