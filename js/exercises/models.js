angular.module('exercisesApp.Models', []).config(function($provide) {
    $provide.factory('exListModel', function() {
        var m = {
            exercises: [{
                type: 'EN-UA',
                text: 'Word Translation EN-UA',
                pictureLink: '/images/en-ua.png'
            }, {
                type: 'UA-EN',
                text: 'Word Translation UA-EN',
                pictureLink: '/images/ua-en.png'
            }, {
                type: 'Build-Word',
                text: 'Build Word',
                pictureLink: '/images/build.png'
            }, {
                type: 'Sound-Word',
                text: 'Sound to Word',
                pictureLink: '/images/sound.png'
            }, {
                type: 'True-False',
                text: 'True or False',
                pictureLink: '/images/sprint.png'
            }, {
                type: 'Do-I-Know',
                text: 'Do I know?',
                pictureLink: '/images/know.png'
            }]
        };
        return m;
    });
    $provide.factory('resultsModel', function () {
        var m = {
            data: []
        };
        m.setData = function (data) {
            m.data = data;
        };
        return m;
    });
    $provide.factory('exModel', function (resultsModel, $http) {
        return {
			data: {
			  current: 0,
			  questions: []
			},
            currentQ: {},

            'next': function () {
			  if (this.data.current < this.data.questions.length) {
				this.data.current++;
			  }
				
            },
            'generateResults': function () {
                var data = [];
                for (var i = 0; i < this.data.questions.length; i++) {
                    data.push({
                        id: this.data.questions[i].question.id,
                        phrase: this.data.questions[i].question.phrase,
                        correct: this.data.questions[i].question.correct
                    });
                }
				this.sendResults(data);
                resultsModel.setData(data);
            },
			fetch: function() {
			  this.data.current = 0;
			  var data;
			  $.ajax({
				url: '/exercises/GetWords',
				async: false,
				dataType: 'JSON',
				data: {
				  type: this.type
				},
				type: "GET"
			  }).done(function(response) {
				data = response;
			  });
			  return data;
			},
			sendResults: function (data) {
			  $.ajax({
				url: '/exercises/processResults',
				async: true,
				dataType: 'JSON',
				data: {
				  type: this.type,
				  results: data
				},
				type: "POST"
			  });
			}
        };
    });
    $provide.factory('exEnUaModel', function(exModel) {
        function ExModel () {};
		exModel.type = 'Word-Translation';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
		  model.data.questions = model.fetch();
		  for (var i = 0; i < model.data.questions.length; i++) {
			model.data.questions[i].question.correct = false;
			model.data.questions[i].question.wasAnswered = false;
		  }
		  return model;
        };

        model.selectAnswer = function (answer) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
            if (currentQ.question.answerId === answer.id) {
                currentQ.question.correct = true;
            }
            return currentQ.question.correct;
        };

        model.showAnswer = function () {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
        };
        return model;
    });
	$provide.factory('exUaEnModel', function(exModel) {
        function ExModel () {};
		exModel.type = 'Translation-Word';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
		  model.data.questions = model.fetch();
		  for (var i = 0; i < model.data.questions.length; i++) {
			model.data.questions[i].question.correct = false;
			model.data.questions[i].question.wasAnswered = false;
		  }
		  return model;
        };

        model.selectAnswer = function (answer) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
            if (currentQ.question.answerId === answer.id) {
                currentQ.question.correct = true;
            }
            return currentQ.question.correct;
        };

        model.showAnswer = function () {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
        };
        return model;
    });
    $provide.factory('exBuildWordModel', function(exModel) {
        function ExModel () {}
		exModel.type = 'BuildWord';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            model.data.questions = model.fetch();
            for (var i = 0; i < model.data.questions.length; i++) {
			  model.data.questions[i].question.correct = true;
			  model.data.questions[i].question.wasAnswered = false;
			  model.data.questions[i].question.wasMistake = false;
			  
			  for (var j = 0; j < model.data.questions[i].symbols.length; j++) {
				model.data.questions[i].symbols[j] = {val: model.data.questions[i].symbols[j]};
			  }
			}
            return model;
        };

        model.selectSymbol = function (symbol) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            if (currentQ.question.phrase[currentQ.currentAnswer.length] === symbol.val) {
                for (var i = currentQ.symbols.length - 1; i >= 0; i--) {
                    delete currentQ.symbols[i].wrong;
                }
                currentQ.currentAnswer += symbol.val;
                currentQ.symbols.splice(currentQ.symbols.indexOf(symbol), 1);
                if (currentQ.currentAnswer === currentQ.question.phrase) {
                    currentQ.question.wasAnswered = true;
                }
            } else {
                symbol.wrong = true;
                currentQ.question.wasMistake = true;
            }
        };

        model.showAnswer = function () {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            for (var i = currentQ.symbols.length - 1; i >= 0; i--) {
                currentQ.symbols[i].wrong = true;
            };
			currentQ.currentAnswer = currentQ.question.phrase;
            currentQ.question.wasAnswered = true;
            currentQ.question.wasMistake = true;
        };

        model.oldNext = model.next;

        model.next = function () {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            if (currentQ.question.wasMistake) {
                currentQ.question.correct = false;
            }
            model.oldNext();
        };
        return model;
    });


    $provide.factory('exSoundWordModel', function(exModel) {
        function ExModel () {};
		exModel.type = 'SoundToWord';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            model.data.questions = model.fetch();
			for (var i = 0; i < model.data.questions.length; i++) {
			  model.data.questions[i].question.correct = false;
			  model.data.questions[i].question.wasAnswered = false;
			}
            return model;
        };

        model.checkAnswer = function (answer) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
            currentQ.question.correct = currentQ.question.phrase === answer;
        };

        model.showAnswer = function () {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            for (var i = currentQ.symbols.length - 1; i >= 0; i--) {
                currentQ.symbols[i].wrong = true;
            }
            currentQ.currentAnswer = currentQ.question.answer;
            currentQ.question.wasAnswered = true;
        };
        return model;
    });
    $provide.factory('exTrueFalseModel', function(exModel) {
        function ExModel () {};
		exModel.type = 'Sprint';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            model.data.questions = model.fetch();
			model.data.remainingTime = 60000;
			model.data.score = 0;
			model.data.deltaScore = 10;
			model.data.correctStack = 0;

			for (var i = 0; i < model.data.questions.length; i++) {
			  model.data.questions[i].question.correct = false;
			}
			
            return model;
        };

        model.checkAnswer = function (val) {
            var question = model.data.questions[model.data.current].question;
            question.correct = question.answer === val;
            if (question.correct) {
                model.data.score += model.data.deltaScore + model.data.deltaScore * model.data.correctStack++;
            } else {
                model.data.correctStack = 0;
            }
        };
        return model;
                
    });
    $provide.factory('exDoIKnowModel', function(exModel) {
        function ExModel () {};
		exModel.type = 'DoIKnow';
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            model.data.questions = model.fetch();

			for (var i = 0; i < model.data.questions.length; i++) {
			  model.data.questions[i].question.correct = false;
			  model.data.questions[i].question.answered = false;
			}
			
            return model;
        };

        model.setAnswer = function (question, val) {
            question.question.answered = true;
            question.question.correct = val;
        };
        return model;
                
    });

});