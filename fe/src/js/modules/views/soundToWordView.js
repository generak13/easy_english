define(['jquery', 'underscore', 'bb', 'handlebars', 'sound-to-word-template', 'exercise-result-template'], function($, _, bb, handlebars, soundToWordTemplate, exerciseResultTemplate) {
	var isCorrectAnswer = false;
	var BuildWordView = bb.View.extend({
		el: '#exercises',
		events: {
			'click .check-answer': 'checkClickHandler',
			'click .question-voice': 'questionVoiceClickHandler',
			'click .forward': 'forwardClickHandler',
			'click .repeat-exercise': 'repeatExersice',
			'click .close-exercise': 'closeExersice',
		},
		initialize: function() {
			this.model.set({
				questionCounter: 0,
				questions: [
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
						}
					}
				]
			});
			//this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			var template = handlebars.compile(soundToWordTemplate),
				model = this.model.toJSON();
			this.$el.html(template(model.questions[model.questionCounter]));
			return this;
		},

		renderResults: function() {
			var template = handlebars.compile(exerciseResultTemplate),
				model = this.model.toJSON();
			this.$el.html(template(model));
			return this;
		},

		checkClickHandler: function(e) {
			if ($('.forward').hasClass('btn-primary')) {
				return;
			}

			var $target = $(e.target);
			var model = this.model.toJSON();
			var answer = $('.answer').val();

			var isCorrectAnswer = this.model.checkAnswer(answer);

			if (isCorrectAnswer) {
				$target.removeClass('btn-default')
					.addClass('btn-success');
			} else {
				$target.removeClass('btn-default')
					.addClass('btn-danger');
			}
			this.markCorrectAnswer();
		},

		questionVoiceClickHandler: function(e) {

		},

		forwardClickHandler: function(e) {
			var $target = $(e.target),
				counter, questions;
			counter = this.model.get('questionCounter');
			questions = this.model.get('questions');

			if ($target.hasClass('btn-primary')) {
				this.model.set({
					'questionCounter': ++counter
				});

				if (counter === questions.length) {
					var obj = [];
					this.renderResults();
					for (var i in questions) {
						obj.push({
							id: questions[i].question.id,
							correct: questions[i].question.correct
						});
					}

				} else {
					this.render();
				}
			} else {
				this.markCorrectAnswer();
				$('.check-answer').removeClass('btn-default')
					.addClass('btn-danger');
			}
		},

		repeatExersice: function() {
			this.initialize();
		},

		closeExersice: function() {
			appEx.router.navigate('', {
				trigger: true
			});
		},

		markCorrectAnswer: function() {
			var counter, questions;

			counter = this.model.get('questionCounter');
			questions = this.model.get('questions');

			$('.forward').removeClass('btn-default')
				.addClass('btn-primary');
			$('.answer').val(questions[counter].question.phrase)
				.attr('readonly', true);
		}
	});

	return BuildWordView;
});