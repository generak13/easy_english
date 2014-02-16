<?php
/* @var $this ExercisesController */

$this->breadcrumbs=array(
	'Exercises'=>array('/exercises'),
	'List',
);
?>

<div id="exercises">
			</div>

<?php
  $baseUrl = Yii::app()->getBaseUrl(true);
  $cs = Yii::app()->getClientScript();
  $cs->registerScriptFile($baseUrl . '/js/vendor/require.js', null, array('data-main' => "js/app.js"));
  $cs->registerScriptFile($baseUrl . '/js/require.config.js');
?>