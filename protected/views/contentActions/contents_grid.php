<?php 
  /**
   * @var content[] models
   */
?>

<table class="contents-list">
  <thead>
    <tr class="row contents-list-header" valign="middle">
      <th class="col-md-9"><?= Yii::t('contents', 'Title')?></th>
      <th class="col-md-1"><?= Yii::t('contents', 'Pages')?></th>
      <th class="col-md-2"><?= Yii::t('contents', 'Date')?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($models as $elem) {?>
    <tr class="row contents-list-item" valign="middle">
      <td class="col-md-9 item-caption"><a href="<?= $this->createUrl('contentActions/show', array('id' => $elem->id))?>"><?= $elem->title?></td>
      <td class="col-md-1 item-pages"><?= $elem->pages?></td>
      <td class="col-md-2 item-data"><?= $elem->date?></td>
    </tr>
    <?php }?>
  </tbody>
</table>
<div id="pages">
  <?php if($pages > 1) {?>
    <?php for($i = 0; $i < $pages; $i++) {?>
      <span><?=($i + 1)?></span>
    <?php }?>
  <?php }?>
</div>