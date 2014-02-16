define(function() {
	return '' + 
	'<div class="question">{{question.translation}}</div>' + 
	'<div class="question-voice"></div>' + 
	'<div class="question-image">' + 
		'<img src="{{question.pictureLink}}">' + 
	'</div>' + 
	'{{#each answers}}<div class="answer btn btn-default" data-answer-id="{{id}}">{{phrase}}</div>{{/each}}' + 
	'<button class="forward btn btn-default">forward</button>';
});