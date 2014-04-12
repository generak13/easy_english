var exercisesApp = angular.module('exercisesApp', ['ui.router.compat', 'exercisesApp.Controllers', 'exercisesApp.Directives']);
exercisesApp.config(function($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/exList');
  $stateProvider
    .state('exList', {
      url: '/exList',
      templateUrl: '/js/templates/exercises-list-tmpl.html',
      controller:  'exListCtrl'
    })
    .state('EN-UA', {
      url: '/EN-UA',
      templateUrl: '/js/templates/en-ua-tmpl.html',
      controller:  'exEnUaCtrl'
    })
    .state('UA-EN', {
      url: '/UA-EN',
      templateUrl: '/js/templates/ua-en-tmpl.html',
      controller:  'exUaEnCtrl'
    })
    .state('Build-Word', {
      url: '/Build-Word',
      templateUrl: '/js/templates/build-word-tmpl.html',
      controller:  'exBuildWordCtrl'
    })
    .state('Sound-Word', {
      url: '/Sound-Word',
      templateUrl: '/js/templates/sound-word-tmpl.html',
      controller:  'exSoundWordCtrl'
    })
    .state('True-False', {
      url: '/True-False',
      templateUrl: '/js/templates/true-false-tmpl.html',
      controller:  'exTrueFalseCtrl'
    })
    .state('Do-I-Know', {
      url: '/do-i-know',
      templateUrl: '/js/templates/do-i-know-tmpl.html',
      controller:  'exDoIKnowCtrl'
    })
    .state('results', {
      url: '/results',
      templateUrl: '/js/templates/results-tmpl.html',
      controller:  'resultsCtrl'
    })
}).run(function ($rootScope, $state) {
  $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState) {
    $state.previous = fromState;
  });
});