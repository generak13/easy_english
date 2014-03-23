angular.module('exercisesApp.Controllers', ['exercisesApp.Models'])
	.controller('exListController', ['$scope', '$state', 'exListModel',

	    function($scope, $state, exListModel) {
	        $scope.model = exListModel;
	        $scope.selectExercise = function (exercise) {
	        	$state.go('' + exercise.type);
	        }
	    }
	])
	.controller('exWordTranslateController', ['$scope', '$state', 'exWordTranslateModel',

	    function($scope, $state, exWordTranslateModel) {
	        $scope.model = exWordTranslateModel;
	        $scope.currentQuestion = $scope.model.question[$scope.current];

	        $scope.selectAnswer = function () {
	        	
	        }
	    }
	])

;