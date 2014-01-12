<?php 
  /**
   * @var content[] models
   */
?>

<table>
  <thead>
    <tr>
      <td><?= Yii::t('content', 'Title')?></td>
      <td><?= Yii::t('content', 'Date')?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($models as $elem) {?>
    <tr>
      <td><a href="<?= $this->createUrl('contentActions/show', array('id' => $elem->id))?>"><?= $elem->title?></td>
      <td><?= $elem->date?></td>
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