require.config({
	baseUrl: "js/",
	paths: {
		"jquery": "vendor/jQuery",
		'underscore': 'vendor/Underscore',
		'bb': 'vendor/Backbone',
		'handlebars': 'vendor/handlebars',


		'router': 'modules/router',

		
		'ExercisesModel': 'modules/models/exercisesModel',


		'ExercisesView': 'modules/views/exercisesView',


		'exercises-list-template': 'templates/exercises-list-template'
	},
	waitSeconds: 1,
});