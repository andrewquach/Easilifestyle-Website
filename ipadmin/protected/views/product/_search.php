<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php
		$category = new CDbCriteria(); 
		$category->order = 'name ASC';
		echo $form->dropDownList($model,'category_id',CHtml::listData(Category::model()->findAll($category), 'id', 'name'), array('prompt'=>'All'));
		?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php
		$status = new CDbCriteria(); 
		$status->order = 'description ASC';
		echo $form->dropDownList($model,'status_id',CHtml::listData(ProductStatus::model()->findAll($status), 'id', 'description'), array('prompt'=>'All'));
		?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->