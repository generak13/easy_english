<?php if ($total > $recordsCount) { ?>
	<?php
	$pagesCount = ceil($total / $recordsPerPage);
	?>
	<ul class="pagination">
		<li <?= ($selectedPage == 1) ? 'class="disabled"' : '' ?> data-page="1>"><a href="#"><?= Yii::t('pagination', 'First') ?></a></li>
		<li <?= ($selectedPage == 1) ? 'class="disabled"' : '' ?> data-page="<?= $selectedPage - 1 ?>"><a href="#">&laquo;</a></li>


		<?php for ($i = 0; $i < $pagesCount; $i++) { ?>
			<li <?php if ($selectedPage == $i + 1) echo 'class="disabled"'; ?> data-page="<?= $i + 1 ?>"><a href="#"><?= $i + 1 ?></a></li>
		<?php } ?>

		<li <?= ($selectedPage == $pagesCount) ? 'class="disabled"' : '' ?> data-page="<?= $selectedPage + 1 ?>"><a href="#">&raquo;</a></li>
		<li <?= ($selectedPage == $pagesCount) ? 'class="disabled"' : '' ?> data-page="<?= $pagesCount ?>>"><a href="#"><?= Yii::t('pagination', 'Last') ?></a></li>
	</ul>
<?php } ?>