<div class="row header">
	<div class="col-md-5">
		<?=Yii::t('translation-list', 'Translation')?>
	</div>
	<div class="col-md-5">
		<?=Yii::t('translation-list', 'Word')?>
	</div>
	<div class="col-md-2 text-center">
		<?=Yii::t('translation-list', 'Approved')?>
	</div>
</div>
<div class="body">
	<?php foreach ($translations as $translation) {?>
		<div class="row translation-block" data-translation="<?=$translation->id?>">
			<div class="translation-text col-md-5"><?=$translation->text?></div>
			<div class="word-text col-md-5"><?=$translation->word->text?></div>
			<div class="col-md-2 row">
				<span class="glyphicon glyphicon-pencil col-md-4 edit-translation"></span>
				<span class="glyphicon glyphicon-remove col-md-4 remove-translation"></span>
				<input class="approve-disapprove col-md-4" type="checkbox" <?=(!is_null($translation->verified_date) ? 'checked' : '')?>/>
			</div>
		</div>
	<?php }?>
</div>
<?=$pagination?>