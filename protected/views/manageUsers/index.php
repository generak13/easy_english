<?php
?>
<div class="row">
	<div class="clearfix col-md-12">
		<div class="row">
      <div class="form-group col-md-offset-1 col-md-4 dictionary-search-container">
        <input type="text" class="form-control" placeholder="<?=Yii::t('manageUsers', 'Search')?>" id="users_search_textfield" value="">
      </div>
      <div class="btn-group col-md-2">
        <button type="button" id="users_search_button" class="btn btn-default"><?=  Yii::t('manageUsers', 'Find')?></button>
      </div>
    </div>
		<div id="users-list">
			<?= $usersList ?>
		</div>
	</div>
	
</div>

<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs->registerCssFile($baseUrl . '/css/jnotify/jquery.jnotify.css');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/jnotify/jquery.jnotify.min.js');
$cs->registerScriptFile($baseUrl . '/js/manageUsers.js');
?>