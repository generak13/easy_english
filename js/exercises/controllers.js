angular.module('exercisesApp.Controllers', ['exercisesApp.Models'])
	.controller('exListCtrl', ['$scope', '$state', 'exListModel',

	    function($scope, $state, exListModel) {
	        $scope.model = exListModel;
	        $scope.selectExercise = function (exercise) {
	        	$state.go('' + exercise.type);
	        }
	    }
	])
	.controller('exEnUaCtrl', ['$scope', '$state', 'exEnUaModel', function($scope, $state, exEnUaModel) {
		$scope.model = exEnUaModel.getData();
		$scope.model.data.questions[$scope.model.data.current];	
		$scope.selectedAnswer = null;

		$scope.selectAnswer = function (answer) {
			$scope.selectedAnswer = answer;
			$scope.model.selectAnswer(answer);
		};

		$scope.showAnswer = function () {
			$scope.model.showAnswer();
		};

		$scope.next = function () {
			if($scope.model.data.current === $scope.model.data.questions.length - 1) {
				$scope.model.generateResults();
				$state.go('results');
			}
			$scope.model.next();
		};
	}])
	.controller('exUaEnCtrl', ['$scope', '$state', 'exUaEnModel', function($scope, $state, exUaEnModel) {
		$scope.model = exUaEnModel.getData();
		$scope.model.data.questions[$scope.model.data.current];	
		$scope.selectedAnswer = null;

		$scope.selectAnswer = function (answer) {
			$scope.selectedAnswer = answer;
			$scope.model.selectAnswer(answer);
		};

		$scope.showAnswer = function () {
			$scope.model.showAnswer();
		};

		$scope.next = function () {
			if($scope.model.data.current === $scope.model.data.questions.length - 1) {
				$scope.model.generateResults();
				$state.go('results');
			}
			$scope.model.next();
		};
	}])
	.controller('exBuildWordCtrl', ['$scope', '$state', 'exBuildWordModel', function ($scope, $state, exBuildWordModel) {
		$scope.model = exBuildWordModel.getData();
		$scope.model.data.questions[$scope.model.data.current];
		$scope.selectedSymbol = null;

		$scope.selectSymbol = function (symbol) {
			$scope.selectedSymbol = symbol;
			$scope.model.selectSymbol(symbol);
		};

		$scope.showAnswer = function () {
			$scope.model.showAnswer();
		};

		$scope.next = function () {
		  
		  if($scope.model.data.current === $scope.model.data.questions.length - 1) {
			  $scope.model.next();
			  $scope.model.generateResults();
			  $state.go('results');
		  }	else {
			  $scope.model.next();
		}

		};
	}])
	.controller('exSoundWordCtrl', ['$scope', '$state', 'exSoundWordModel', function ($scope, $state, exSoundWordModel) {
		$scope.model = exSoundWordModel.getData();
		$scope.model.data.questions[$scope.model.data.current];

		$scope.checkAnswer = function (answer) {
			$scope.model.checkAnswer(answer);
		};

		$scope.showAnswer = function () {
			$scope.model.showAnswer();
		};

		$scope.next = function () {		
			if($scope.model.data.current === $scope.model.data.questions.length - 1) {
				$scope.model.generateResults();
				$state.go('results');
			}
			$scope.currentAnswer = '';
			$scope.model.next();
		};
	}])
	.controller('exTrueFalseCtrl', ['$scope', '$state', 'exTrueFalseModel', function ($scope, $state, exTrueFalseModel) {
		$scope.model = exTrueFalseModel.getData();
		$scope.model.data.questions[$scope.model.data.current];

		$scope.checkAnswer = function (val) {
	        $scope.model.checkAnswer(val);
	        $scope.next();
		};

		$scope.next = function () {
			$scope.model.next();
			if($scope.model.data.current === $scope.model.data.questions.length) {
				$scope.model.generateResults();
				$state.go('results');
			}
		};

		$scope.finish = function () {
			$scope.model.generateResults();
			$state.go('results');
		};
	}])
	.controller('exDoIKnowCtrl', ['$scope', '$state', '$stateParams','exDoIKnowModel', function ($scope, $state, $stateParams, exDoIKnowModel) {
		$scope.model = exDoIKnowModel.getData();
		$scope.model.data.questions[$scope.model.data.current];

		$scope.setAnswer = function (question, val) {
	        $scope.model.setAnswer(question, val);
		};

		$scope.exit = function () {
			$state.go('exList');
		};

		$scope.again = function () {
			$state.transitionTo($state.current, $stateParams, { reload: true, inherit: false, notify: true });
		};
	}])
	.controller('resultsCtrl', ['$scope', '$state', 'resultsModel', function ($scope, $state, resultsModel) {
		if (resultsModel.data.length > 0) {
			$scope.model = resultsModel;
		} else {
			$state.go('exList');
		}

		$scope.repeatEx = function () {
			$state.go($state.previous.name);
		}

		$scope.goToList = function () {
			$state.go('exList');
		};
		
	}])
