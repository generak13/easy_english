define(['underscore', 'bb'], function ($,bb,_) {
	
	var ExercisesModule = bb.extend({
		defaults: {
			exercises: [];
		}
	});

	return  ExercisesModule;
});