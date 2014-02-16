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
			'ChooseAnswer_UA-EN': 'ChooseAnswer_UA-EN',
			'ChooseAnswer_EN-UA': 'ChooseAnswer_EN-UA',
			'BuildWord': 'BuildWord',
			'SoundToWord': 'SoundToWord'
		},
		'doExList': function() {
			var model = new ExercisesModel({
						exercises: [
								{
									title: 'ChooseAnswer UA-EN',
									type: 'ChooseAnswer_UA-EN'
								},
								{
									title: 'ChooseAnswer EN-UA',
									type: 'ChooseAnswer_EN-UA'
								},
								{
									title: 'BuildWord',
									type: 'BuildWord'
								},
								{
									title: 'SoundToWord',
									type: 'SoundToWord'
								},
								{
									title: 'ex5',
									type: 'ex5'
								},
								{
									title: 'ex6',
									type: 'ex6'
								}

							]
					});
			var view = new ExercisesView({
					model: model
				}
			);
		},

		'ChooseAnswer_UA-EN': function() {
			this.ChooseAnswer('ChooseAnswer_UA-EN');
		},

		'ChooseAnswer_EN-UA': function() {
			this.ChooseAnswer('ChooseAnswer_UA-EN');
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
