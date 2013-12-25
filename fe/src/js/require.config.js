require.config({
	baseUrl: "js/",
	paths: {
		"$": "vendor/jQuery",
		'underscore': 'vendor/Underscore',
		'bb': 'vendor/Backbone',
		'handlebars': 'vendor/handlebars',


		'router': 'modules/router',

		
		'exercisesView': 'modules/models/exercisesView',


		'exercisesView': 'modules/views/exercisesView',


		'exercises-list-template': 'templates/exercises-list-template'
	},
	waitSeconds: 1,
});