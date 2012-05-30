<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	)
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'url'); ?>
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
	
	<?php if (!$model->isNewRecord): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->checkbox($model,'enabled'); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>
	<?php endif; ?>
	
	<?php if (!$model->isNewRecord && !empty($model->thumbnail)) : ?>
	<div class="row">
		<?php echo $form->labelEx($model,'Current Thumbnail'); ?>
		<?php echo CHtml::image(Yii::app()->params['siteurl']."/assets/products/".$model->thumbnail, "Thumbnail"); ?>
	</div>
	<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'thumbnail'); ?>
		<?php echo $form->fileField($model,'thumbnail',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'thumbnail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'old_price'); ?>
		<?php echo $form->textField($model,'old_price',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'old_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'el_points'); ?>
		<?php echo $form->textField($model,'el_points',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'el_points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bv_points'); ?>
		<?php echo $form->textField($model,'bv_points',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'bv_points'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php
		$status = new CDbCriteria(); 
		$status->order = 'description ASC';
		echo $form->dropDownList($model,'status_id',CHtml::listData(ProductStatus::model()->findAll($status), 'id', 'description'), array('prompt'=>'No Status'));
		?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<?php
		$category = new CDbCriteria();
		$category->condition = 'enabled=1'; 
		$category->order = 'name ASC';
		echo $form->dropDownList($model,'category_id',CHtml::listData(Category::model()->findAll($category), 'id', 'name'));
		?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->