<div class="row">
  <div class="col-md-offset-3 col-md-6">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'register-form',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'action' => 'register',
    'method' => 'post'
    )); ?>
      <div class="well">
      <h2>Registration form</h2>
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'email', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textField($model, 'email', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'email');?>
        </div>
      
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'login', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textField($model, 'login', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'login');?>
        </div>
      
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'password', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'password');?>
        </div>
      
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'repeat_password', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'repeat_password', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'repeat_password');?>
        </div>
      
        <div class="form-group row">
          <div class="col-lg-3">
            <button class="btn btn-primary" type="submit">Register</button>
          </div>
        </div>
      </div>
    <?php $this->endWidget(); ?>
  </div>
</div>