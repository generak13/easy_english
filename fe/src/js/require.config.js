require.config({
	baseUrl: "js/",
	paths: {
		"jquery": "vendor/jQuery",
		'underscore': 'vendor/Underscore',
		'bb': 'vendor/Backbone',
		'handlebars': 'vendor/handlebars',


		'router': 'modules/router',

		
		'ExercisesModel': 'modules/models/exercisesModel',
		'ChooseAnswerModel': 'modules/models/chooseAnswerModel',


		'ExercisesView': 'modules/views/exercisesView',
		'ChooseAnswerView': 'modules/views/chooseAnswerView',


		'exercises-list-template': 'templates/exercises-list-template',
		'choose-answer-template': 'templates/choose-answer-template',
		'exercise-result-template': 'templates/exercise-result-template'
	},
	waitSeconds: 1,
});