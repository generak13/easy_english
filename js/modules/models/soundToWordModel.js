define(['underscore', 'bb'], function(_, bb) {

    var SoundToWordModule = bb.Model.extend({
        defaults: {
            questionCounter: 0,
            questions: []
        },
        checkAnswer: function(str) {
            var questions = this.get('questions'),
              num = this.get('questionCounter'),
              phrase = questions[num].question.phrase;
            if (phrase === str) {
                questions[num].question.correct = true;
            }
            return (phrase === str);
        },
        'fetch': function() {
            var data;

            $.ajax({
                url: '/exercises/getWords',
                async: false,
                data: {
                    type: this.attributes.type
                },
                dataType: 'JSON',
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

    return SoundToWordModule;
});