<?php
/* @var $this DictionaryController */
/* @var $data Dictionary */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('word_id')); ?>:</b>
	<?php echo CHtml::encode($data->word_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('translation_id')); ?>:</b>
	<?php echo CHtml::encode($data->translation_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('context')); ?>:</b>
	<?php echo CHtml::encode($data->context); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('added_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->added_datetime); ?>
	<br />


</div>