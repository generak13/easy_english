<div class="row content" data-content='<?= $content->id ?>'>
	<div class="col-md-12">
		<span class="content-name"><?= $content->title ?></span>
		<?php if($content->owner_id == Yii::app()->user->id) {?>
			<a href="/contentActions/edit/<?=$content->id?>" class="glyphicon glyphicon-pencil" title="<?=Yii::t('content', 'Edit')?>"></a>
		<?php }?>
	</div>
	<?php if ($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) { ?>
		<div class="col-md-5 video">
			<?= $content->player_link ?>
		</div>
	<?php } ?>
	<?=$textContent?>
	<div class="col-md-2">
		<div class='words_wrapper'></div>
		<div class='sentense-translation'></div>
	</div>
</div>

<div class="dictionary-search-results">
  <div class="add-your-translation btn btn-link">
    <?=Yii::t('addWordDialog', 'Add your translation')?>
  </div>
  <div class="your-translation" style="display: none;">
    <?=Yii::t('addWordDialog', 'Your translation:')?>
    <input type="text" class="form-control" id="custom_translation" placeholder="your translation">
  </div>
</div>

<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs->registerCssFile($baseUrl . '/css/jnotify/jquery.jnotify.css');

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/jnotify/jquery.jnotify.min.js');
$cs->registerScriptFile($baseUrl . '/js/show_content.js');
?>