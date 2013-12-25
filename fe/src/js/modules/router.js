define([
	'jquery',
	'underscore',
	'bb',
	'ExercisesView',
	'ExercisesModel'
], function($, _, bb, ExercisesView, ExercisesModel) {
	var Router = bb.Router.extend({
		routes: {
			'': 'doExList',
			'/ex1': 'doEx1'
		},
		'doExList': function() {
			var model = new ExercisesModel({
						exercises: [
								{
									title: 'ex1',
									type: 'ex1'
								},
								{
									title: 'ex2',
									type: 'ex2'
								},
								{
									title: 'ex3',
									type: 'ex3'
								}

							]
					});
			var view = new ExercisesView({
					model: model
				}
			);
		}
	});

	var initialize = function() {
		var router = new Router();
		bb.history.start();
		router.navigate('doExList');
	}
	return {
		initialize: initialize,
	}
});
