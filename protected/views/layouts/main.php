<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css">
<!--		<script type="text/javascript" src="//www.google.com/jsapi"></script>
		<script type="text/javascript">
      google.load('visualization', '1', {packages: ['imagelinechart']});
    </script>-->
      <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>

  <body>
    <header class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<?= CHtml::link(Yii::t('menu', 'Easy English'), '/', array('class' => 'navbar-brand')) ?>
				</div>
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<?= CHtml::link(Yii::t('menu', 'About'), array('site/about')) ?>
						</li>

						<?php if (Yii::app()->user->isGuest) { ?>
							<li>
								<?= CHtml::link(Yii::t('menu', 'Registration'), array('mySite/register')) ?>
							</li>
							<li>
								<?= CHtml::link(Yii::t('menu', 'Login'), array('mySite/login')) ?>
							</li>
						<?php } else { ?>
							<li>
								<?= CHtml::link(Yii::t('menu', 'Contents'), array('contentActions/list')) ?>
							</li>
							<li>
								<?= CHtml::link(Yii::t('menu', 'Dictionary'), array('dictionary/dictionary')) ?>
							</li>                
							<li class="logout-gravatar">
								<img src="https://s.gravatar.com/avatar/<?= md5(user::model()->find('id=:id', array(':id' => Yii::app()->user->id))->email) ?>?s=45"></img>
								<ul>
									<li><?= CHtml::link(Yii::t('menu', 'Profile'), array('mySite/settings'))?></li>
									<li><?= CHtml::link(Yii::t('menu', 'Logout'), array('mySite/logout')) ?></li>
								</ul>
							</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			</div>
		</header>
		<div class="container">
			<div class="well">
				<?php echo $content; ?>
			</div>
		</div><!-- page -->

	</body>
</html>
