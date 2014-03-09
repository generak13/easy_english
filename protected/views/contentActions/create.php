<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'create-content-form',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'action' => 'create',
    'method' => 'post'
    )); ?>
      <div class="well">
        <h2><?=Yii::t('contents', 'Create content')?></h2>
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'title', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textField($model, 'title', array('class' => 'form-control'));?>
          </div>
        </div>
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'genre', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'genre', array('tourism' => Yii::t('contents', 'Tousirm'), 'it' => Yii::t('contents', 'IT'), 'rest' => Yii::t('contents', 'Rest')), array('class' => 'col-lg-3 control-label form-control selectpicker'));?>
          </div>
        </div>


        <div class="form-group row">
          <?php echo $form->labelEx($model, 'type', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'type', array('text' => Yii::t('contents', 'Text'), 'video' => Yii::t('contents', 'Video'), 'audio' => Yii::t('contents', 'Audio')), array('class' => 'col-lg-3 control-label form-control selectpicker'));?>
          </div>
        </div>

        <div class="form-group row">
          <?php echo $form->labelEx($model, 'player-link', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textArea($model, 'player_link', array('class' => 'form-control'));?>
          </div>
        </div>

        <div class="form-group row">
          <?php echo $form->labelEx($model, 'lvl', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'lvl', array('easy' => Yii::t('contents', 'Easy'), 'medium' => Yii::t('contents', 'Medium'), 'hard' => Yii::t('contents', 'Hard')), array('class' => 'col-lg-3 control-label form-control selectpicker'));?>
          </div>
        </div>


        <div class="form-group row">
          <?php echo $form->labelEx($model, 'text', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textArea($model, 'text', array('class' => 'form-control'));?>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-offset-9 col-lg-3">
            <button class="btn btn-primary btn-block" type="submit"><?=  Yii::t('contents', 'Create')?></button>
          </div>
        </div>
      </div>
    <?php $this->endWidget(); ?>
  </div>
</div>

<?php
  Yii::app()->clientScript->registerCoreScript('jquery');     
  Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/create_content.js');
?>