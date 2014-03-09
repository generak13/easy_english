<div class="users">
	<div class="user header">
		<div class="user-header-login col-md-3"><?= Yii::t('manageUser', 'Login')?></div>
		<div class="user-header-email col-md-3"><?= Yii::t('manageUsers', 'Email')?></div>
		<div class="user-header-register col-md-3"><?=  Yii::t('manageUsers', 'Register')?></div>
		<div class="user-header-role col-md-3"><?=  Yii::t('manageUser', 'Role')?></div>
	</div>
	<?php foreach ($users as $user) {?>
	<div class="user row" data-user="<?=$user->id?>">
		<div class="user-item-login col-md-3"><?=$user->login?></div>
		<div class="user-item-email col-md-3"><?=$user->email?></div>
		<div class="user-item-register col-md-3"><?=$user->register?></div>
		<div class="user-item-role col-md-3">
			<select>
				<option value="0" <?=($user->type == 0) ? 'selected' : ''?>><?=  Yii::t('manageUsers', 'User')?></option>
				<option value="1" <?=($user->type == 1) ? 'selected' : ''?>><?=  Yii::t('manageUsers', 'Moderator')?></option>
				<option value="2" <?=($user->type == 2) ? 'selected' : ''?>><?=  Yii::t('manageUsers', 'Administator')?></option>
			</select>
		</div>
	</div>
	<?php }?>
	
	<?=$pagination?>
</div>