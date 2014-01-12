<?php
/* @var $this DictionaryController */
/* @var $model Dictionary */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dictionary-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'word_id'); ?>
		<?php echo $form->textField($model,'word_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'word_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'translation_id'); ?>
		<?php echo $form->textField($model,'translation_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'translation_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'context'); ?>
		<?php echo $form->textField($model,'context',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'context'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'added_datetime'); ?>
		<?php echo $form->textField($model,'added_datetime'); ?>
		<?php echo $form->error($model,'added_datetime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->