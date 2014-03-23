var exercisesApp = angular.module('exercisesApp', ['ui.router.compat', 'exercisesApp.Controllers']);
exercisesApp.config(function($stateProvider, $urlRouterProvider) {
  $urlRouterProvider.otherwise('/exList');
  $stateProvider
    .state('exList', {
      url: '/exList',
      templateUrl: 'js/templates/exercises-list-tmpl.html',
      controller:  'exListController'
    })
    .state('EN-UA', {
      url: '/EN-UA',
      templateUrl: 'js/templates/exercises-word-translate-tmpl.html',
      controller:  'exWordTranslateController'
    })
    .state('UA-EN', {
      url: '/UA-EN',
      templateUrl: 'js/templates/exercises-list-tmpl.html',
      controller:  'exListController'
    })
    .state('Build-Word', {
      url: '/Build-Word',
      templateUrl: 'js/templates/exercises-list-tmpl.html',
      controller:  'exListController'
    })
    .state('Sound-Word', {
      url: '/Sound-Word',
      templateUrl: 'js/templates/exercises-list-tmpl.html',
      controller:  'exListController'
    })
    .state('True-False', {
      url: '/True-False',
      templateUrl: 'js/templates/exercises-list-tmpl.html',
      controller:  'exListController'
    })
});