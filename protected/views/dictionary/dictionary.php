<div class="row">
  <div class="well clearfix col-md-12">
    <div class="row">
      <div class="form-group col-md-offset-1 col-md-4 dictionary-search-container">
        <input type="text" class="form-control" placeholder="Search" id="word_search_textfield" value="<?=$text?>">
        <div class="dictionary-search-results">
          <div class="dictionary-search-results-item">
            sdfsdf
          </div>
          <div class="dictionary-search-results-item">
            sdfsdf
          </div>
          <div class="dictionary-search-results-item">
            sdfsdf
          </div>
          <div class="add-your-translation btn btn-link">
            Add your translation
          </div>
          <div class="your-translation">
            Your translation:
            <input type="text" class="form-control" id="custom_translation" placeholder="your translation">
          </div>
        </div>
      </div>
      <div class="btn-group col-md-2">
        <button type="button" id="word_search_button" class="btn btn-default">Find</button>
        <button type="button" id="add_word" class="btn btn-default">Add</button>
      </div>
      
      <div id='dictionary_list'>
        <?= $dictionary_list ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="https://rawgithub.com/hiddentao/google-tts/master/google-tts.min.js"></script>

<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/dictionary.js');
?>