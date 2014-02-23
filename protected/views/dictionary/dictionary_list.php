<div class="dictionary-items">
	<?php if (!count($dictionary)) { ?>
		<div>
			<?=Yii::t('dictionary', 'Words are not found')?>
		</div>
	<?php } ?>
	<?php foreach ($dictionary as $key => $elem) { ?>
	  <div class="dictionary-item row" data-dictionary="<?= $elem['dictionary_id'] ?>">
	    <div class="dictionary-item-phrase col-md-8">
	      <div class="sound-icon" data-sound="<?= $elem['sound'] ?>">
	        <span class="glyphicon glyphicon-play-circle">
	        </span>
	      </div>
				<?= $elem['word'] ?> - <?= implode("; ", $elem['translations']) ?>
	    </div>
	    <div class="dictionary-item-add-date col-md-3">
				<?= date("M j, Y, g:i a", strtotime($elem['date'])) ?>
	    </div>
	    <div class="dictionary-item-delete col-md-1">
	      <span class="glyphicon glyphicon-remove">
	      </span>
	    </div>
	  </div>
	<?php } ?>

	<?php if ($total > count($dictionary)) { ?>
		<?php 
			$pagesCount = ceil($total / $words_per_page);
		?>
		<ul class="pagination">
			<li <?= ($selected_page == 1) ? 'class="disabled"' : '' ?> data-page="1>"><a href="#"><?=Yii::t('dictionary', 'First')?></a></li>
			<li <?= ($selected_page == 1) ? 'class="disabled"' : '' ?> data-page="<?= $selected_page - 1 ?>"><a href="#">&laquo;</a></li>

			
			<?php for ($i = 0; $i < $pagesCount; $i++) { ?>
				<li <?php if ($selected_page == $i + 1) echo 'class="disabled"'; ?> data-page="<?= $i + 1?>"><a href="#"><?= $i + 1?></a></li>
			<?php } ?>

			<li <?= ($selected_page == $pagesCount) ? 'class="disabled"' : '' ?> data-page="<?= $selected_page + 1 ?>"><a href="#">&raquo;</a></li>
			<li <?= ($selected_page == $pagesCount) ? 'class="disabled"' : '' ?> data-page="<?= $pagesCount ?>>"><a href="#"><?=Yii::t('dictionary', 'Last')?></a></li>
		</ul>
	<?php } ?>
</div>