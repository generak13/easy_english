<div class="dictionary-items">
	<?php if (!count($dictionary)) { ?>
		<div>
			<?=Yii::t('dictionary', 'Words are not found')?>
		</div>
	<?php } ?>
	<?php foreach ($dictionary as $key => $elem) { ?>
	  <div class="dictionary-item row" data-dictionary="<?= $elem['dictionary_id'] ?>">
	    <div class="dictionary-item-phrase col-md-8" data-word="<?=$elem['word_id']?>">
	      <div class="sound-icon" data-sound="<?= $elem['sound'] ?>">
	        <span class="glyphicon glyphicon-play-circle">
	        </span>
	      </div>
				<?= $elem['word'] ?> - <?= implode("; ", $elem['translations']) ?>
	    </div>
	    <div class="dictionary-item-add-date col-md-3">
				<?= date("M j, Y, g:i a", strtotime($elem['date'])) ?>
	    </div>
	    <div class="col-md-1">
				<span class="glyphicon glyphicon-pencil dictionary-item-edit"></span>
	      <span class="glyphicon glyphicon-remove dictionary-item-delete"></span>
	    </div>
	  </div>
	<?php } ?>

	<?=$pagination?>
</div>