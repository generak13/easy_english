<div class="row content" data-content='<?= $content->id ?>'>
	<div class="col-md-12">
		<h2><?= $content->title ?></h2>
	</div>
	<?php if ($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) { ?>
		<div class="col-md-5 video">
			<?= $content->player_link ?>
		</div>
	<?php } ?>
	<div class="col-md-5">
		<div class='content-text'>
			<?= $content->text ?>
		</div>
		<div class="paginator clearfix">
			<button type="button" class="btn btn-default paginator-prev">
				<span class="glyphicon glyphicon-chevron-left"></span><?=Yii::t('contents', 'prev')?>

				<button type="button" class="btn btn-default paginator-next pull-right">
					<?=  Yii::t('contents', 'next')?><span class="glyphicon glyphicon-chevron-right"></span>
				</button>
		</div>
		<div>
			<?php if ($is_learned) { ?>
				<button id='set-learned' class='btn btn-primary all-width disabled'><?= Yii::t('contents', 'I have leanred all unknown words') ?></button>
			<?php } else { ?>
				<button id='set-learned' class='btn btn-success all-width' ><?= Yii::t('contents', 'I have leanred all unknown words') ?></button>
			<?php } ?>
		</div>

	</div>
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