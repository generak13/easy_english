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
            }]
        }
        return m;
    });
    $provide.factory('exWordTranslateModel', function() {
        var m = {
            current: 0,
            questions: [{
                question: {
                    id: 1,
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
                    phrase: '1',
                    pictureLink: '1',
                    voiceLink: '1',
                    answerId: 3,
                    correct: false
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
        }
        return m;
    });
});