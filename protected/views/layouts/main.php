<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container">

		<header class="navbar navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
          <?=  CHtml::link("Some Logo/Text here", '/', array('class' => 'navbar-brand'))?>
				</div>
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li>
							<?=  CHtml::link("About", array('site/about'))?>
						</li>
            
            <?php if(Yii::app()->user->isGuest) {?>
              <li>
                <?=  CHtml::link("Registration", array('mySite/register'))?>
              </li>
              <li>
                <?=  CHtml::link("Login", array('mySite/login'))?>
              </li>
            <?php } else {?>
              <li>
                <?=  CHtml::link("Logout", array('mySite/logout'))?>
              </li>
            <?php }?>
					</ul>
				</nav>
			</div>
		</header>

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>
