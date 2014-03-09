<div class="row">
  <div class="clearfix col-md-12">
    <div class="row">
      <div class="form-group col-md-offset-1 col-md-4 dictionary-search-container">
        <input type="text" class="form-control" placeholder="<?=  Yii::t('dictionary', 'Search')?>" id="word_search_textfield" value="<?= $text ?>">
        <div class="dictionary-search-results" style="display: none;">
          <div class="add-your-translation btn btn-link">
            <?=  Yii::t('addWordDialog', 'Add your translation')?>
          </div>
          <div class="your-translation" style="display: none;">
            <?=  Yii::t('addWordDialog', 'Your translation:')?>
            <input type="text" class="form-control" id="custom_translation" placeholder="your translation">
          </div>
        </div>
      </div>
      <div class="btn-group col-md-2">
        <button type="button" id="word_search_button" class="btn btn-default"><?=Yii::t('dictionary', 'Find')?></button>
        <button type="button" id="add_word" class="btn btn-default"><?=  Yii::t('dictionary', 'Add')?></button>
      </div>
    </div>
		<div id='dictionary_list'>
			<?= $dictionary_list ?>
		</div>
  </div>
</div>
<!--<div id="edit-dictionary-dialog" title="Edit" class="clearfix" style="display: none;">
	
</div>-->

<div id="edit-dictionary-dialog" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edit</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="https://rawgithub.com/hiddentao/google-tts/master/google-tts.min.js"></script>
<script	type="text/template" id="edit-dictionary-template">
	<div class='row'>
		<div class='selected-translation col-md-6'>
			{{#each data}}
				{{#ifEqual translation_id ../selected}}
					<div class='text'>{{text}}</div>
					<div class='translation-image'>
						<img src='{{image_url}}'/>
					</div>
					<div class='form-group'>
						<input type='text' class='form-control change-image' placeholder='#link for new image#' value='{{image_url}}'>
					</div>
					Context:
					<div class='context'>{{context}}</div>
					<div class='form-group'>
						<input type='text' class='form-control change-context' placeholder='#new context#' value='{{context}}'>
					</div>
				{{/ifEqual}}
			{{/each}}
		</div>
		<div class='translations-list col-md-6'>
			{{#each data}}
					<div class='translations-list-item {{#ifEqual translation_id ../selected}}selected{{/ifEqual}}' data-id='{{translation_id}}'>
						{{text}}
						<div class='remove'>X</div>
					</div>
			{{/each}}
		</div>
	</div>
</script>

<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs->registerCssFile($baseUrl . '/css/jnotify/jquery.jnotify.css');
$cssCoreUrl = $cs->getCoreScriptUrl();
// now that we know the core folder, register 
$cs->registerCssFile($cssCoreUrl . '/jui/css/base/jquery-ui.css'); 

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/vendor/handlebars.js');
$cs->registerScriptFile($baseUrl . '/js/vendor/bootstrap.min.js');
$cs->registerScriptFile($baseUrl . '/js/jnotify/jquery.jnotify.min.js');
$cs->registerScriptFile($baseUrl . '/js/dictionary.js');
?>