<div class="form-group">
    <input type="text" class="form-control" id="word_search_textfield" placeholder="Search">
    <button id="word_search_button" class="btn btn-default">Find</button>
    <button id="add_word" class="btn btn-default">Add</button>
</div>
<div id='dictionary_list'>
  <?=$dictionary_list?>
</div>
<script type="text/javascript" src="https://rawgithub.com/hiddentao/google-tts/master/google-tts.min.js"></script>

<?php
  $baseUrl = Yii::app()->getBaseUrl(true);
  $cs = Yii::app()->getClientScript();
  
  Yii::app()->clientScript->registerCoreScript('jquery');     
  Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
  $cs->registerScriptFile($baseUrl . '/js/dictionary.js');
?>