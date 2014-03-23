define(function() {
	return '' +
	'<div id="sound-to-word">' +
		'<div class="question-voice">' +
				'<div class="sound-icon glyphicon glyphicon-volume-up" data-sound={{question.voiceLink}}></div>' +
		'</div>' +
		'<div class="form-row">' +
			'<div class="col-md-offset-3 col-md-5">' +
				'<input type="text" class="answer form-control" />' +
			'</div>' +
			'<button class="btn btn-default check-answer">Check</button>' +
		'</div>' +
		'<div class="col-md-offset-2 col-md-4">' +
			'<div class="question-image">' + 
				'<img src="{{question.pictureLink}}">' + 
			'</div>' +
			'<div class="question-context">{{question.context}}</div>' +
		'</div>' +
		'<button class="forward btn btn-default col-md-offset-2">forward</button>' +
	'</div>';
});