define(function() {
  return '' + 
  '<div id="exercise-word-translation">' +
	'<div class="row">' +
	  '<div class="col-md-3 col-md-offset-2">' +
		'<div class="question">' +
		  '<span class="glyphicon glyphicon-play-circle" data-link="{{question.phrase}}"></span>' +
		  '{{question.phrase}}'+
		'</div>' +
		'<div class="question-image">' + 
		  '<img src="{{question.pictureLink}}">' +
		'</div>' + 
	  '</div>' +
	  '<div class="col-md-4 col-md-offset-1">' +
		'<div class="answers">' +
		  '{{#each answers}}' + 
			'<div class="answer btn btn-default" data-answer-id="{{id}}">{{phrase}}</div>' +
		  '{{/each}}' +
		'</div>' +
		'<div class="forward btn btn-default">forward</div>'
	  '</div>' +
	'</div>' +
  '</div>';
});