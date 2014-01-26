<div class="row content" data-content='<?=$content->id?>'>
  <div class="well clearfix">
					<div class="col-md-12">
						<h2><?=$content->title?></h2>
					</div>
          <?php if($content->type == content::$TYPE_AUDIO || $content->type == content::$TYPE_VIDEO) {?>
            <div class="col-md-5 video">
              <?=$content->player_link?>
            </div>
          <?php }?>
					<div class="col-md-5">
						<?=$content->text?>
						<div class="row">
							<button type="button" class="col-lg-2 btn btn-default btn-lg">
								<span class="glyphicon glyphicon-chevron-left">prev</span>
							</button>
							<button type="button" class="col-lg-offset-8 col-lg-2 btn btn-default btn-lg">
								<span class="glyphicon glyphicon-chevron-right">next</span>
							</button>
						</div>
            <div>
              <button id='set-learned'><?= Yii::t('show_content', 'I have leanred all unknown words')?></button>
            </div>
					
					</div>
					<div class="col-md-2 words_wrapper">
					</div>
  </div>
</div>

<div class="dictionary-search-results" style="display: none;background-color: green;">
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

<?php 
  $baseUrl = Yii::app()->getBaseUrl(true);
  $cs = Yii::app()->getClientScript();
  
  Yii::app()->clientScript->registerCoreScript('jquery');     
  Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
  $cs->registerScriptFile($baseUrl . '/js/show_content.js');
?>