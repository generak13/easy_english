<div class="col-md-<?=$isTextType ? 10 : 5?>">
		<div class='content-text'>
			<?= $text ?>
		</div>
		<?=$pagination?>
		<div>
			<?php if ($isLearned) { ?>
				<button id='set-learned' class='btn btn-primary all-width disabled'><?= Yii::t('contents', 'I have leanred all unknown words') ?></button>
			<?php } else { ?>
				<button id='set-learned' class='btn btn-success all-width' ><?= Yii::t('contents', 'I have leanred all unknown words') ?></button>
			<?php } ?>
		</div>
	</div>