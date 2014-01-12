define(['underscore', 'bb'], function (_,bb) {
	
	var ExercisesModule = bb.Model.extend({
		defaults: {
			exercises: []
		}
	});

	return  ExercisesModule;
});