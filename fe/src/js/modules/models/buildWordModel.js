define(['underscore', 'bb'], function (_, bb) {
	
	var BuildWordModule = bb.Model.extend({
		defaults: {
			questionCounter: 0,
			questions: [
				{
					question: {
						id: 1,
						phrase: 'bo-bo',
						translation: 'bo-bo',
						pictureLink: '1',
						voiceLink: '1',
						correct: false,
						wasMistake: false
					},
					symbols: ['b','o','-','b,','o'],
					currentAnswer: ''
				}
			]
		},
		checkAnswer: function (symbol) {
			if ($('.forward').hasClass('btn-primary')) {
				return;
			}

			var questions = this.get('questions'),
				num = this.get('questionCounter'),
				symbols = questions[num].symbols,
				currentAnswer = questions[num].currentAnswer;

			if (questions[num].question.phrase[currentAnswer.length] === symbol) {
				currentAnswer += symbol;
				questions[num].currentAnswer = currentAnswer;
				var i = symbols.indexOf(symbol);
				if (i > -1) {
					symbols.splice(i, 1);
				}
				return true;
			} else {
				questions[num].question.wasMistake = true;
				return false;
			}	
		},

		'fetch': function () {

		}
	});

	return BuildWordModule;
});