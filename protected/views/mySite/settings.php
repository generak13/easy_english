<div class="row">
	<div class="col-md-3">
		<div class="span3 settings-avatar">
			<div class="center">
				<img class="user-avatar" src="https://s.gravatar.com/avatar/<?= md5(user::model()->find('id=:id', array(':id' => Yii::app()->user->id))->email) ?>?s=180">
			</div>
			<div class="change-avatar-help">
				<div class="center">
					<div class="help-block small muted"><?= Yii::t('profile', 'Change your avatar with <a href="http://www.gravatar.com" target="_blank">Gravatar</a>.') ?></div>
				</div>

				<div id="how-to-change-avatar" class="help-block small how-to-change-avatar-hide in collapse" style="height: auto;">
					<hr>
					<h5 class="center"><?= Yii::t('profile', 'How to change your avatar') ?></h5>
					<ol>
						<li><?= Yii::t('profile', 'Go to <a href="http://www.gravatar.com" target="_blank">http://www.gravatar.com</a>') ?></li>
						<li><?= Yii::t('profile', 'Click <a href="http://gravatar.com/site/signup/" target="_blank">sign up</a>') ?></li>
						<li><?= Yii::t('profile', 'Enter your email address (<b>same as in easy-english-yzi.me</b>), within a few minutes, you will receive a new email with your account activation from gravatar.') ?></li>
						<li><?= Yii::t('profile', 'Follow the instructions given in your email and finish the steps in the <a href="http://www.gravatar.com" target="_blank">http://www.gravatar.com</a>.') ?></li>
						<li><?= Yii::t('profile', 'Please allow 5 to 10 minutes for avatar changes to take effect.') ?></li>
						<li><?= Yii::t('profile', 'Your picture will be visible in your Easy English profile after the changes to take effect.') ?></li>
					</ol><hr>	
				</div> 
			</div>
		</div>
	</div>

	<div class="col-md-9">
		<div class="row change-email">
			<div class='form-group row'>
				<label class='col-lg-2 control-label text-right reqired' for="login"><?= Yii::t('profile', 'Login:') ?></label>
				<div class='col-lg-5'>
					<input class='form-control' id="login" name="login" type="text" value="<?= $user->login ?>" />
				</div>
				
			</div>
			<div class='form-group row'>
				<label class='col-lg-2 control-label text-right required'  for="email"><?= Yii::t('profile', 'Email:') ?></label>
				<div class='col-lg-5'>
					<input class='form-control' id="email" name="email" type="text" value="<?= $user->email ?>" />
				</div>
			</div>
			<div class="col-md-offset-5 col-md-2 form-group">
				<button class='btn btn-primary all-width' id="change-login-email" type="submit"><?= Yii::t('profile', 'Change') ?></button>
			</div>
		</div>

		<div class="row change-password">
			<div class='form-group row'>
				<label class='col-lg-2 control-label text-right required'  for="password"><?= Yii::t('profile', 'New password:') ?></label>
				<div class='col-lg-5'>
					<input class='form-control' id="password" name="password" type="text" value="" />
				</div>
			</div>
			<div class='form-group row'>
				<label class='col-lg-2 control-label text-right required'  for="repeat_password"><?= Yii::t('profile', 'Repeat password:') ?></label>
				<div class='col-lg-5'>
					<input class='form-control' id="repeat_password" name="repeat_password" type="text" value="" />
				</div>
			</div>
			<div class="col-md-offset-5 col-md-2 form-group">
				<button class='btn btn-primary all-width' id="change-password" type="submit"><?= Yii::t('profile', 'Change') ?></button>
			</div>
		</div>
		<h3>Week Statistic</h3>
		<div class="statistic-chart-container"></div>
	</div>
</div>


<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs->registerCssFile($baseUrl . '/css/jnotify/jquery.jnotify.css');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/jnotify/jquery.jnotify.min.js');
$cs->registerScriptFile($baseUrl . '/js/settings.js');
?>