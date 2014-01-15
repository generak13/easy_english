<?php
/**
 * @var content[] $contents
 */
?>

<div class="row">
  <div class="well clearfix">
    <div class="content-filter  row">
      <form class="col-md-6">
        <div class="form-group col-md-10">
          <input id="search-title" type="text" class="form-control" placeholder="Search">
        </div>
        <button id="search-submit" type="submit" class="btn btn-default col-md-2">
          Submit
        </button>
      </form>
      <div class="col-md-3">
        <select id="search-genre" class="selectpicker form-control">
          <option value="tourism"><?= Yii::t('content', 'Tousirm')?></option>
          <option value="IT"><?= Yii::t('content', 'IT')?></option>
          <option value="rest"><?= Yii::t('content', 'Rest')?></option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="search-lvl" class="selectpicker form-control">
          <option value="0"><?= Yii::t('content', 'Easy')?></option>
          <option value="1"><?= Yii::t('content', 'Medium')?></option>
          <option value="2"><?= Yii::t('content', 'Hard')?></option>
        </select>
      </div>
    </div>
    <div class="col-md-2">
      <ul class="content-nav" id="content-status">
        <li data-content-status="all-content" class="selected">
          <div>
            <span>
              All Content
            </span>
          </div>
        </li>
        <li data-content-status="learning">
          <div>
            <span>
              Learning
            </span>
          </div>
        </li>
        <li data-content-status="learned">
          <div>
            <span>
              Learned
            </span>
          </div>
        </li>
        <li>
          <div>
            <span>
              Add Content
            </span>
          </div>
        </li>
      </ul>
    </div>
    <?php
//    if(!$contents) {
//      echo 'Немає записів';
//    }
//    foreach ($contents as $content) {?>
    <div class="content-search-result row">
      <div class="content-search-item col-md-10 row">
        <div class="col-md-10">
          <span class="content-type-icon">
          </span>
          <h4 class="content-title">
            <?php // CHtml::link($content->title, array('contentActions/show', 'id' => $content->id));?>
          </h4>
        </div>
        <div class="col-md-2">
          <span class="content-recent">
            <?=1?>
          </span>
        </div>
      </div>
      <div id="grid">
        <?php $this->renderPartial('contents_grid', array('models' => $contents, 'pages' => $pages))?>
      </div>
    <?php // }?>
    </div>
    
    <?php // $this->renderPartial('content_list_grid', array('model' => $model))?>
  </div>
</div>

<?php 
  $baseUrl = Yii::app()->getBaseUrl(true);
  $cs = Yii::app()->getClientScript();
  
  Yii::app()->clientScript->registerCoreScript('jquery');     
  Yii::app()->clientScript->registerCoreScript('jquery.ui'); 
  $cs->registerScriptFile($baseUrl . '/js/content.js');
?>