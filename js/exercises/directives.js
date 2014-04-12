/**
 * exercises.Directives Module
 *
 * Description
 */
angular.module('exercisesApp.Directives', []).directive('playAudio', [
    function() {
        return {
            restrict: 'A',
            template: '<audio></audio>',
            scope: {
                audioLink: '=',
                autoplay: '='
            },
            link: function(scope, elem, attrs) {
                attrs.$observe("playAudio", function(playAudio) {
                    params = scope.$eval(attrs.playAudio);
                    audio.src = params.audioLink;
                    if (params.autoplay) {
                        audio.src = audio.src;
                        audio.play();
                    }
                });
                var params = scope.$eval(attrs.playAudio),
                    audio = angular.element(elem).find('audio')[0];
                audio.src = params.audioLink;
                audio.autoplay = params.autoplay;
                elem.bind('click', function() {
                    audio.src = audio.src;
                    audio.play();
                });
            }
        };
    }
]).directive('timer', [
    function() {
        return {
        	restrict: 'E',
        	template: '<h2>{{remainingTime / 1000}}</h2>',
        	scope: {
        		remainingTime: '=remainingTime',
        		callback: '&onFinish'
        	},
        	link: function(scope) {
        		var timerInterval = setInterval(function () {
        			if (scope.remainingTime < 2000) {
        				scope.callback();
        				clearInterval(timerInterval);
        			}
        			scope.$apply(function () {
        				scope.remainingTime -= 1000;
        			})
        		}, 1000);
	        }
        }
    }
]).directive('ngEnter', [
    function() {
        return {
	        link: function(scope, element, attrs) {
	            element.bind("keydown keypress", function(event) {
	                if (event.which === 13) {
	                    scope.$apply(function() {
	                        scope.$eval(attrs.ngEnter);
	                    });
	                    event.preventDefault();
	                }
	            });
	        }
	    }
    }
]).directive('setFocus', function($timeout) {
    return {
        link: function(scope, element, attrs) {
            scope.$watch(attrs.setFocus, function(value) {
                if (value === true) {
                    console.log('value=', value);
                    element[0].focus();
                }
            });
        }
    };
})