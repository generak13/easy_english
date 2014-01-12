define(function() {
	return '' + 
	'<div class="question">{{question.phrase}}</div>' + 
	'<div class="question-voice"></div>' + 
	'<div class="question-image">' + 
		'<img src="{{question.pictureLink}}">' + 
	'</div>' + 
	'{{#each answers}}<div class="answer" data-answer-id="{{id}}">{{phrase}}</div>{{/each}}' + 
	'<button class="btn btn-primary forward">forward</button>';
});