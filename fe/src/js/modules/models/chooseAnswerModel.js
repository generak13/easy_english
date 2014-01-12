define(['underscore', 'bb'], function (_, bb) {
	
	var ChooseAnswerModule = bb.Model.extend({
		defaults: {
			questionCounter: 0,
			questions: [
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				},
				{
					question: {
						id: 1,
						phrase: '1',
						pictureLink: '1',
						voiceLink: '1',
						answerId: 3,
						correct: false
					},
					answers: [ 
						{
							id: 1,
							phrase: '1'
						},
						{
							id: 2,
							phrase: '2'
						},
						{
							id: 3,
							phrase: '3'
						},
						{
							id: 4,
							phrase: '4'
						}
					]
				}
			]
		},
		checkAnswer: function (id) {
			var questions = this.get('questions');
				num = this.get('questionCounter')
			if (id === questions[num].question.answerId) {
				questions[num].question.correct = true;
				return true;
			}
			return false;
		}
	});

	return  ChooseAnswerModule;
});