<?php
/**
 * @var content[] $contents
 */
?>
<div class="content-filter row">
	<form class="col-md-6">
		<div class="form-group col-md-10">
			<input id="search-title" type="text" class="form-control" placeholder="<?=Yii::t('contents', 'Search')?>">
		</div>
		<button id="search-submit" type="submit" class="btn btn-default col-md-2">
			<?=  Yii::t('contents', 'Find')?>
		</button>
	</form>
	<div class="col-md-2">
		<select id="search-genre" class="selectpicker form-control">
			<option value="tourism"><?= Yii::t('contents', 'Tousirm') ?></option>
			<option value="IT"><?= Yii::t('contents', 'IT') ?></option>
			<option value="rest"><?= Yii::t('contents', 'Rest') ?></option>
		</select>
	</div>
	<div class="col-md-2">
		<select id="search-lvl">
			<option value="0"><?= Yii::t('contents', 'Easy') ?></option>
			<option value="1"><?= Yii::t('contents', 'Medium') ?></option>
			<option value="2"><?= Yii::t('contents', 'Hard') ?></option>
		</select>
	</div>
	<div class="col-md-2">
		<div id="search-type">
			<div type="all" class="selected"><?=  Yii::t('contents', 'All')?></div>
			<div type="video" class="glyphicon glyphicon-facetime-video"></div>
			<div type="audio" class="glyphicon glyphicon-music"></div>
			<div type="text" class="glyphicon glyphicon-book"></div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<ul class="content-nav" id="content-status">
			<li data-content-status="all-content" class="selected">
				<div>
					<span>
						<?=  Yii::t('contents', 'All Content')?>
					</span>
				</div>
			</li>
			<li data-content-status="learning">
				<div>
					<span>
						<?=  Yii::t('contents', 'Learning')?>
					</span>
				</div>
			</li>
			<li data-content-status="learned">
				<div>
					<span>
						<?=Yii::t('contents', 'Learned')?>
					</span>
				</div>
			</li>
			<li>
				<div>
					<a href="/contentActions/create">
						<?=  Yii::t('contents', 'Add Content')?>
					</a>
				</div>
			</li>
		</ul>
	</div>
	<?php
	//    if(!$contents) {
	//      echo 'Немає записів';
	//    }
	//    foreach ($contents as $content) {
	?>
	<div class="content-search-result col-md-10">
		<div id="grid">
			<?php $this->renderPartial('contents_grid', array('models' => $contents, 'pages' => $pages)) ?>
		</div>
		<?php // } ?>
	</div>

	<?php // $this->renderPartial('content_list_grid', array('model' => $model)) ?>
</div>


<?php
$baseUrl = Yii::app()->getBaseUrl(true);
$cs = Yii::app()->getClientScript();

$cs = Yii::app()->getClientScript();
$cs->packages = array(
	'jquery.ui' => array(
		'js' => array('jui/js/jquery-ui.min.js'),
		'css' => array('jui/css/base/jquery-ui.css'),
		'depends' => array('jquery'),
	),
);
$cs->registerCoreScript('jquery.ui');


Yii::app()->clientScript->registerCoreScript('jquery');
//  Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/content.js');
$cs->registerScriptFile($baseUrl . '/js/multiselect/multiselect.js');

//  Yii::app()->clientScript->registerCoreCss('jquery.ui');
$cs->registerCssFile($baseUrl . '/css/multiselect.css');
?>