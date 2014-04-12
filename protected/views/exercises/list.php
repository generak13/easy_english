<?php
/* @var $this ExercisesController */

$this->breadcrumbs=array(
	'Exercises'=>array('/exercises'),
	'List',
);
?>

<div ng-app="exercisesApp" id="exercises">
	<div ui-view></div>
</div>

<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
  $baseUrl = Yii::app()->getBaseUrl(true);
  $cs = Yii::app()->getClientScript();
	
	
	$cs->registerScriptFile($baseUrl . '/js/vendor/angular-1.2.15.min.js');
	$cs->registerScriptFile($baseUrl . '/js/vendor/ui-router-0.2.10.js');
	
	$cs->registerScriptFile($baseUrl . '/js/exercises/directives.js');
	$cs->registerScriptFile($baseUrl . '/js/exercises/models.js');
	$cs->registerScriptFile($baseUrl . '/js/exercises/controllers.js');
	$cs->registerScriptFile($baseUrl . '/js/exercises/exercisesApp.js');
?>
