define(['jquery', 'underscore', 'bb', 'handlebars', 'choose-answer-template', 'exercise-result-template'], function($, _, bb, handlebars, chooseAnswerTemplate, exerciseResultTemplate) {
	var isCorrectAnswer = false;
	var ChooseAnswerView = bb.View.extend({
		el: '#exercises',
		events: {
			'click .answer': 'answerClickHandler',
			'click .question-voice': 'questionVoiceClickHandler',
			'click .forward': 'forwardClickHandler',
			'click .repeat-exercise': 'repeatExersice',
			'click .close-exercise': 'closeExersice',
		},
		initialize: function() {
			this.model.set({
				questionCounter: 0,
				questions: [{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}, {
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [{
						id: 1,
						phrase: '1'
					}, {
						id: 2,
						phrase: '2'
					}, {
						id: 3,
						phrase: '3'
					}, {
						id: 4,
						phrase: '4'
					}]
				}]
			});
			//this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			var template = handlebars.compile(chooseAnswerTemplate),
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

		answerClickHandler: function(e) {
			var $target = $(e.target);
			var model = this.model.toJSON();
			var id = $target.data()['answerId'];

			if ($('.forward').hasClass('btn-primary')) {
				return;
			}

			isCorrectAnswer = this.model.checkAnswer(id);

			if (!isCorrectAnswer) {
				$target.removeClass('btn-default').addClass('btn-danger');
			}

			this.markCorrectAnswer();

		},

		questionVoiceClickHandler: function(e) {

		},

		forwardClickHandler: function(e) {
			var $target = $(e.target),
				counter, questions;

			if ($target.hasClass('btn-primary')) {
				counter = this.model.get('questionCounter');
				questions = this.model.get('questions');
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
					isCorrectAnswer = false;
					this.render();
				}
			} else {
				this.markCorrectAnswer();
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
			var correctAnswerId = this.model.get('questions')[this.model.get('questionCounter')].question.answerId,
				$ca = this.$el.find('[data-answer-id="' + correctAnswerId + '"]');
			$ca.removeClass('btn-default').addClass('btn-success');

			$('.forward').removeClass('btn-default').addClass('btn-primary');
		}
	});

	return ChooseAnswerView;
});