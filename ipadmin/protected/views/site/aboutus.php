<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'About Us / FAQ',
);
?>

<h1>About Us / FAQ</h1>
<?php if ($saved): ?>
<p class="note">Content has been saved successfully.</p>
<?php endif; ?>
<div class="form">
<?php 
echo CHtml::beginForm();
?>
	<div class="row">
		<?php echo CHtml::label('About Us/FAQ', 'aboutus'); ?>
		<?php 
		$this->widget('ext.ckeditor.CKEditorWidget',array(
			"name"=>'aboutus',
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
