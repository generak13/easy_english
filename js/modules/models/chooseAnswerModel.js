define(['underscore', 'bb', 'voice'], function (_, bb) {
	
	var ChooseAnswerModule = bb.Model.extend({
		defaults: {
			type: 'ChooseAnswer_UA-EN',
			questionCounter: 0,
			questions: []
		},
		checkAnswer: function (id) {
			var questions = this.get('questions'),
				num = this.get('questionCounter');
			if (id == questions[num].question.answerId) {
				questions[num].question.correct = true;
				return true;
			}
			return false;
		},
    
    'fetch': function() {
      var data;
      
			$.ajax({
				url: '/exercises/GetWords',
        async: false,
        dataType: 'JSON',
        data: {
          type: this.get('type')
        },
        type: "GET"
			}).done(function(response) {
        data = response;
      });
      return data;
		},
    
    'sendResults': function() {
      var obj = [];
      for (var i in this.get('questions')) {
        obj.push({
          id: this.get('questions')[i].question.id,
          correct: this.get('questions')[i].question.correct
        });
      }

      $.post(
        '/exercises/ProcessResults',
        {
          type: this.get('type'),
          results: obj
        }
      );
    }
	});

	return  ChooseAnswerModule;
});