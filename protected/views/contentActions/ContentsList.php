<div class="content-list-wrapper">
<div class="row">
	<div class="col-md-5">
		<input type="text" class="form-control" placeholder="<?=  Yii::t('dictionary', 'Search')?>" id="content_search_textfield" value="">
	</div>
	<div class="col-md-1">
		<button type="button" id="content_search_button" class="btn btn-default"><?=Yii::t('dictionary', 'Find')?></button>
	</div>
</div>
	<div id="content-list">
		<?=$contentsTable?>
	</div>
</div>	
<div id="edit-content-dialog" title="Edit Content" style="display: none;">
	<div>
		<?=Yii::t('a', 'Translation:')?> <input class="translation" type="text" value=""/>
	</div>
	<div>
		<?=Yii::t('a', 'Word:')?> <input class="word" type="text" disabled />
	</div>
</div>
	
<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs->registerCssFile($baseUrl . '/css/jnotify/jquery.jnotify.css');
$cssCoreUrl = $cs->getCoreScriptUrl();

// now that we know the core folder, register 
$cs->registerCssFile($cssCoreUrl . '/jui/css/base/jquery-ui.css'); 

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/jnotify/jquery.jnotify.min.js');
$cs->registerScriptFile($baseUrl . '/js/contentsList.js');
?>