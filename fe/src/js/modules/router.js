define([
	'jquery',
	'underscore',
	'bb',
	'ExercisesModel',
	'ChooseAnswerModel',
	'ExercisesView',
	'ChooseAnswerView'
], function($, _, bb, ExercisesModel, ChooseAnswerModel, ExercisesView, ChooseAnswerView) {
	var Router = bb.Router.extend({
		routes: {
			'': 'doExList',
			'ChooseAnswer_UA-EN': 'ChooseAnswer_UA-EN',
			'ChooseAnswer_EN-UA': 'ChooseAnswer_EN-UA'
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
									title: 'ex3',
									type: 'ex3'
								},
								{
									title: 'ex4',
									type: 'ex4'
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

		'ChooseAnswer_UA-EN': function() {debugger;
			var model = new ChooseAnswerModel();
			var view = new ChooseAnswerView({
					model: model
				}
			);
		},

		initialize: function() {
			bb.history.start();
			this.navigate('', {trigger: true});
		}
	});

	return Router;
});
