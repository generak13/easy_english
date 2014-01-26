<div class="dictionary-items">
  <?php foreach ($dictionary as $elem) {?>
  <div class="dictionary-item row">
    <div class="dictionary-item-phrase col-md-7">
      <div class="sound-icon" data-sound="<?=$elem['sound']?>">
        <span class="glyphicon glyphicon-play-circle">
        </span>
      </div>
      <?=$elem['word']?> - <?=  implode("; ", $elem['translations'])?>
    </div>
    <div class="dicionary-item-image col-md-1">
      <img src="" alt="">
    </div>
    <div class="dictionary-item-add-date col-md-3 text-center">
      <?=date("M j, Y, g:i a", strtotime($elem['date']))?>
    </div>
    <div class="dictionary-item-delete col-md-1">
      <span class="glyphicon glyphicon-remove">
      </span>
    </div>
  </div>
  <?php }?>
</div>
