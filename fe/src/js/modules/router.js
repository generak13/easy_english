define([
	'$',
	'underscore',
	'bb',
	'exercisesView'
], function($, _, bb, exList) {
	var Router = bb.Router.extend({
		routes: {
			'': 'doExList',
			'/ex1': 'doEx1'
		}
	});

	var initialize = function() {
		var router = new Router();
		router.on('doExList', function() {
			var view = new exercisesView({
				model: 
					[
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
				}
			);
		});
	}
	return {
		initialize: initialize
	}
});
