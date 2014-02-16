define(['underscore', 'bb'], function (_, bb) {
	
	var BuildWordModule = bb.Model.extend({
		defaults: {
			questionCounter: 0,
			questions: []
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
      var data;
      debugger;
			$.ajax({
				url: '/exercises/GetWords',
        async: false,
        dataType: 'JSON',
        data: {
          type: this.attributes.type
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

	return BuildWordModule;
});