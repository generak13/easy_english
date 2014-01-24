define(['underscore', 'bb'], function (_, bb) {
	
	var ChooseAnswerModule = bb.Model.extend({
		defaults: {
			type: 'ChooseAnswer_UA-EN',
			questionCounter: 0,
			questions: [
				{
					question: {
						id: 1,
						phrase: 'bo-bo',
						pictureLink: '1',
						voiceLink: '1',
						correct: false
					},
					symbols: ['b','o','-','b,','o'],
					currentAnswer: ''
				}
			]
		},
		checkAnswer: function (symbol) {
			var questions = this.get('questions'),
				num = this.get('questionCounter'),
				currentAnswer = questions[num].currentAnswer;

			if (questions[num].question.phrase[currentAnswer.length] === symbol) {
				currentAnswer += symbol;
				return true;
			} else {
				return false;
			}
		},

		'fetch': function () {

		}
	});

	return  ChooseAnswerModule;
});