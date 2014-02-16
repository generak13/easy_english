define(['underscore', 'bb'], function (_, bb) {
	
	var SoundToWordModule = bb.Model.extend({
		defaults: {
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
		},
		checkAnswer: function (str) {
			var questions = this.get('questions'),
				num = this.get('questionCounter'),
				phrase = questions[num].question.phrase;
			if (phrase === str) {
				questions[num].question.correct = true;
			}
			return (phrase === str);
		},

		'fetch': function () {
      var data;
      
			$.ajax({
				url: '/exercises/getExcerciseList',
        async: false,
        dataType: 'JSON',
        data: {
          type: this.type
        },
        type: "POST"
			}).done(function(response) {
        data = response;
      });
      return data;
		}
	});

	return SoundToWordModule;
});