require.config({
	baseUrl: "../js/",
	paths: {
		"jquery": "vendor/jQuery",
		'underscore': 'vendor/Underscore',
		'bb': 'vendor/Backbone',
		'handlebars': 'vendor/handlebars',


		'router': 'modules/router',

		
		'ExercisesModel': 'modules/models/exercisesModel',
		'ChooseAnswerModel': 'modules/models/chooseAnswerModel',
		'BuildWordModel': 'modules/models/buildWordModel',
		'SoundToWordModel': 'modules/models/soundToWordModel',


		'ExercisesView': 'modules/views/exercisesView',
		'ChooseAnswerView': 'modules/views/chooseAnswerView',
		'BuildWordView': 'modules/views/buildWordView',
		'SoundToWordView': 'modules/views/soundToWordView',


		'exercises-list-template': 'templates/exercises-list-template',
		'choose-answer-template': 'templates/choose-answer-template',
		'build-word-template': 'templates/build-word-template',
		'sound-to-word-template': 'templates/sound-to-word-template',
		'exercise-result-template': 'templates/exercise-result-template'
	},
	waitSeconds: 1,
});
