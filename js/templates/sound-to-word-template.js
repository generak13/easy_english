define(function() {
	return '' + 
	'<div class="question-voice"></div>' + 
	'<div class="question-image">' + 
		'<img src="{{question.pictureLink}}">' + 
	'</div>' + 
	'<input type="text" class="answer" />' +
	'<button class="btn btn-default check-answer">Check</button>' +
	'<button class="forward btn btn-default">forward</button>';
});