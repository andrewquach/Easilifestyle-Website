<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'frm_search',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'promotion_id'); ?>
		<?php
		$c = new CDbCriteria(); 
		$c->order = 'title ASC';
		echo $form->dropDownList($model,'promotion_id',CHtml::listData(Promotion::model()->findAll($c), 'id', 'title'), array('prompt'=>'All'));
		?>
		<?php echo $form->error($model,'promotion_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'from'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'attribute'=>'from',
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
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'to'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'attribute'=>'to',
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
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->