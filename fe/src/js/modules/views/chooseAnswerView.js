define(['jquery', 'underscore', 'bb', 'handlebars', 'choose-answer-template', 'exercise-result-template'], function ($, _, bb, handlebars, chooseAnswerTemplate, exerciseResultTemplate) {
	var isCorrectAnswer = false;
	var ChooseAnswerView = bb.View.extend({
		el: '#exercises',
		events: {
			'click .answer': 'answerClickHandler',
			'click .question-voice': 'questionVoiceClickHandler',
			'click .forward': 'forwardClickHandler'
		},
		initialize: function () {
			//this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			var template = handlebars.compile(chooseAnswerTemplate),
				model = this.model.toJSON();
			this.$el.html(template(model.questions[model.questionCounter]));
			return this;
		},

		renderResults: function () {
			var template = handlebars.compile(exerciseResultTemplate),
				model = this.model.toJSON();
			this.$el.html(template(model.questions[model.questionCounter]));
			return this;
		},

		answerClickHandler: function(e) {
			var model = this.model.toJSON();
			var id = $(e.target).data()['answerId'];
			isCorrectAnswer = this.model.checkAnswer(id);
			alert(isCorrectAnswer);
		},

		questionVoiceClickHandler: function(e) {

		},

		forwardClickHandler: function(e) {
			var counter = this.model.get('questionCounter'),
				questions = this.model.get('questions');
			this.model.set({'questionCounter': ++counter});
			if (counter === questions.length) {
				var obj = [];
				for(var i in questions) {
					obj.push( {
						id: questions[i].question.id,
						correct: questions[i].question.correct
					});
				}
			}
			isCorrectAnswer = false;
			this.render();
		}
	});

	return ChooseAnswerView;
});