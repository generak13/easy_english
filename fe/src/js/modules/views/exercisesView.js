define(['jquery', 'underscore', 'bb', 'handlebars', 'exercises-list-template'], function ($, _, bb, handlebars, exercisesListTemplate) {
	
	var ExercisesView = bb.View.extend({
		el: '#exercises',
		events: {
			'click div': 'chooseExercise'
		},
		initialize: function () {
			this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			var template = handlebars.compile(exercisesListTemplate);
			this.$el.html(template(this.model.toJSON()));
			return this;
		},

		chooseExercise: function(e) {
			alert(this);
		}
	});

	return ExercisesView;
});