define([
	'jquery',
	'underscore',
	'bb',
	'ExercisesModel',
	'ChooseAnswerModel',
	'BuildWordModel',
	'SoundToWordModel',
	'ExercisesView',
	'ChooseAnswerView',
	'BuildWordView',
	'SoundToWordView'
], function($, _, bb, ExercisesModel, ChooseAnswerModel, BuildWordModel, SoundToWordModel, ExercisesView, ChooseAnswerView, BuildWordView, SoundToWordView) {
	var Router = bb.Router.extend({
		routes: {
			'': 'doExList',
			'Translation-Word': 'ChooseAnswer_UA-EN',
			'Word-Translation': 'ChooseAnswer_EN-UA',
			'BuildWord': 'BuildWord',
			'SoundToWord': 'SoundToWord'
		},
		'doExList': function() {
			var model = new ExercisesModel();
			var view = new ExercisesView({
					model: model
				}
			);
		},

		'ChooseAnswer_UA-EN': function() {
			this.ChooseAnswer('Translation-Word');
		},

		'ChooseAnswer_EN-UA': function() {
			this.ChooseAnswer('Word-Translation');
		},

		'ChooseAnswer': function(type) {
			var model = new ChooseAnswerModel({
				type: type
			});
			var view = new ChooseAnswerView({
					model: model
				}
			);
		},

		'BuildWord': function() {
			var model = new BuildWordModel({
				type: 'BuildWord'
			});
			var view = new BuildWordView({
					model: model
				}
			);
		},

		'SoundToWord': function() {
				var model = new SoundToWordModel({
				type: 'SoundToWord'
			});
			var view = new SoundToWordView({
					model: model
				}
			);
		},

		initialize: function() {
			bb.history.start();
		}
	});

	return Router;
});
