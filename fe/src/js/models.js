angular.module('exercisesApp.Models', []).config(function($provide) {
    $provide.factory('exListModel', function() {
        var m = {
            exercises: [{
                type: 'EN-UA',
                text: 'Word Translation EN-UA'
            }, {
                type: 'UA-EN',
                text: 'Word Translation UA-EN'
            }, {
                type: 'Build-Word',
                text: 'Build Word'
            }, {
                type: 'Sound-Word',
                text: 'Sound to Word'
            }, {
                type: 'True-False',
                text: 'True or False'
            }, {
                type: 'Do-I-Know',
                text: 'Do I know?'
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
    $provide.factory('exModel', function (resultsModel) {
        return {
            model: {},
            current: 0,
            currentQ: {},

            'next': function () {
                this.data.current++;
            },
            'generateResults': function () {
                var data = [];
                for (var i = 0; i < this.data.questions.length; i++) {
                    data.push({
                        id: this.data.questions[i].question.id,
                        phrase: this.data.questions[i].question.phrase,
                        isCorrect: this.data.questions[i].question.correct
                    });
                }
                resultsModel.setData(data);
            }
        };
    });
    $provide.factory('exWordTranslateModel', function(exModel) {
        function ExModel () {};
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            var data = {
                current: 0,
                questions: [{
                    question: {
                        id: 1,
                        phrase: '1',
                        pictureLink: '1',
                        voiceLink: 'http://cdn.mos.musicradar.com//audio/samples/dubstep-demo-loops/DS_BeatF145-01.mp3',
                        answerId: 3,
                        correct: false,
                        wasAnswered: false
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
                        phrase: '2',
                        pictureLink: '1',
                        voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                        answerId: 3,
                        correct: false,
                        wasAnswered: false
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
                        phrase: '3',
                        pictureLink: '1',
                        voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                        answerId: 3,
                        correct: false,
                        wasAnswered: false
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
            };
            model.data = data;
            current = model.data.current;
            currentQ = model.data.questions[current];
            return model;
        };

        model.selectAnswer = function (answer) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            currentQ.question.wasAnswered = true;
            if (currentQ.answerId === answer.id) {
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
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            var data = {
                current: 0,
                questions: [
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo translation',
                            answer: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                            correct: true,
                            wasMistake: false,
                            wasAnswered: false
                        },
                        symbols: [{val:'b'},{val:'o'},{val:'-'},{val:'b'},{val:'o'}],
                        currentAnswer: ''
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo translation',
                            answer: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                            correct: true,
                            wasMistake: false,
                            wasAnswered: false
                        },
                        symbols: [{val:'b'},{val:'o'},{val:'-'},{val:'b'},{val:'o'}],
                        currentAnswer: ''
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo translation',
                            answer: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                            correct: true,
                            wasMistake: false,
                            wasAnswered: false
                        },
                        symbols: [{val:'b'},{val:'o'},{val:'-'},{val:'b'},{val:'o'}],
                        currentAnswer: ''
                    }
                ]
            };
            model.data = data;
            current = model.data.current;
            currentQ = model.data.questions[current];
            return model;
        };

        model.selectSymbol = function (symbol) {
            var current = model.data.current;
            var currentQ = model.data.questions[current];
            if (currentQ.question.answer[currentQ.currentAnswer.length] === symbol.val) {
                for (var i = currentQ.symbols.length - 1; i >= 0; i--) {
                    delete currentQ.symbols[i].wrong;
                }
                currentQ.currentAnswer += symbol.val;
                currentQ.symbols.splice(currentQ.symbols.indexOf(symbol), 1);
                if (currentQ.currentAnswer === currentQ.question.answer) {
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
            currentQ.currentAnswer = currentQ.question.answer;
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
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            var data = {
                current: 0,
                questions: [
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://soundbible.com/grab.php?id=1949&type=mp3',
                            context: 'bo-bo context',
                            wasAnswered: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://www.tonycuffe.com/mp3/tailtoddle_lo.mp3',
                            context: 'bo-bo context',
                            wasAnswered: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'bo-bo',
                            pictureLink: '1',
                            voiceLink: 'http://soundbible.com/grab.php?id=1949&type=mp3',
                            context: 'bo-bo context',
                            wasAnswered: false,
                            correct: false
                        }
                    }
                ]
            };
            model.data = data;
            current = model.data.current;
            currentQ = model.data.questions[current];
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
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            data = {
                current: 0,
                remainingTime: 60000,
                score: 0,
                deltaScore: 10,
                correctStack: 0,
                questions: [
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'sdasd',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіdsasdл',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіл',
                            answer: true,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'sdasd',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіdsasdл',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіл',
                            answer: true,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'sdasd',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіdsasdл',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіл',
                            answer: true,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'sdasd',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіdsasdл',
                            answer: false,
                            correct: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            textTranslation: 'стіл',
                            answer: true,
                            correct: false
                        }
                    },
                ]
            };
            model.data = data;
            current = model.data.current;
            currentQ = model.data.questions[current];
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
        ExModel.prototype = exModel;
        var model = new ExModel();

        model.getData = function () {
            data = {
                current: 0,
                remainingTime: 60000,
                score: 0,
                deltaScore: 10,
                correctStack: 0,
                questions: [
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                    {
                        question: {
                            id: 1,
                            phrase: 'table',
                            translation: 'sdasd',
                            answered: false,
                            known: false
                        }
                    },
                ]
            };
            model.data = data;
            current = model.data.current;
            currentQ = model.data.questions[current];
            return model;
        };

        model.setAnswer = function (question, val) {
            question.question.answered = true;
            question.question.known = val;
        };
        return model;
                
    });

});