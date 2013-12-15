<div class="row">
  <div class="col-md-offset-3 col-md-6">
    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions' => array(
      'validateOnSubmit' => true
    ),
    'action' => 'login',
    'method' => 'post'
    )); ?>
      <div class="well">
      <h2>Login form</h2>
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'username', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->textField($model, 'username', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'username');?>
        </div>
      
        <div class="form-group row">
          <?php echo $form->labelEx($model, 'password', array('class' => 'col-lg-3 control-label'));?>
          <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'password', array('class' => 'form-control'));?>
          </div>
          <?php echo $form->error($model, 'password');?>
        </div>
      
        <div class="form-group row">
          <div class="col-lg-3">
            <button class="btn btn-primary" type="submit">Login</button>
          </div>
        </div>
      </div>
    <?php $this->endWidget(); ?>
  </div>
</div>