define(['jquery', 'underscore', 'bb', 'handlebars', 'build-word-template', 'exercise-result-template'], function($, _, bb, handlebars, buildWordTemplate, exerciseResultTemplate) {
	var isCorrectAnswer = false;
	var BuildWordView = bb.View.extend({
		el: '#exercises',
		events: {
			'click .symbol': 'symbolClickHandler',
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
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					},
					{
						question: {
							id: 1,
							phrase: 'bo-bo',
							translation: '_bo-bo',
							pictureLink: '1',
							voiceLink: '1',
							correct: false,
							wasMistake: false
						},
						symbols: ['b','-','b','o','o'],
						currentAnswer: ''
					}
				]
			});
			//this.listenTo(this.model, 'change', this.render);
			this.render();
		},

		render: function() {
			var template = handlebars.compile(buildWordTemplate),
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

		symbolClickHandler: function(e) {
			if ($('.forward').hasClass('btn-primary')) {
				return;
			}

			var $target = $(e.target);
			var model = this.model.toJSON();
			var symbol = $target.data()['symbol'];

			var isCorrectAnswer = this.model.checkAnswer(symbol);

			if (isCorrectAnswer) {
				this.render();
			} else {
				$target.removeClass('btn-default')
					.addClass('btn-danger');
			}
		},

		questionVoiceClickHandler: function(e) {

		},

		forwardClickHandler: function(e) {
			var $target = $(e.target),
				counter, questions;
			counter = this.model.get('questionCounter');
			questions = this.model.get('questions');

			if ($target.hasClass('btn-primary')) {
				var wasMistake = questions[counter].question.wasMistake,
					currentAnswer = questions[counter].currentAnswer,
					phrase = questions[counter].question.phrase,
					isCorrectAnswer;
				isCorrectAnswer = (currentAnswer === phrase) && !wasMistake;

				questions[counter].question.correct = isCorrectAnswer;
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
				$target.addClass('btn-primary');
				$('.currentAnswer').html(questions[counter].question.phrase);
			}
		},

		repeatExersice: function() {
			this.initialize();
		},

		closeExersice: function() {
			appEx.router.navigate('', {
				trigger: true
			});
		}
	});

	return BuildWordView;
});