<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'App Introduction (for sharing)',
);
?>

<h1>App Introduction</h1>
<?php if ($saved): ?>
<p class="note">Content has been saved successfully.</p>
<?php endif; ?>
<div class="form">
<?php 
echo CHtml::beginForm();
?>
	<div class="row">
		<?php echo CHtml::label('App Introduction', 'appintro'); ?>
		<?php 
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"name"=>'appintro',
			"defaultValue"=>$file_content,
			"config" => array(
				"height"=>"400px",
				"width"=>"600px",
				"toolbar"=>Yii::app()->params['ckeditor']
			)
		));
		?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>
<?php
echo CHtml::endForm(); 
?>
</div><!-- form -->
