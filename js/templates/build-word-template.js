define(function() {
	return '' +
	'<div id="world-build">' + 
		'<div class="question">{{question.translation}}</div>' +
		'<div class="current-answer">{{currentAnswer}}</div>' +
		'<div class="symbols">' +
			'{{#each symbols}}' +
				'<div class="symbol btn btn-default" data-symbol="{{this}}">{{this}}</div>' +
			'{{/each}}' +
		'</div>' +		
		'{{#if symbols.length}}' +
		'{{else}}' +
			'<div class="question-voice">' +
				'<div class="sound-icon glyphicon glyphicon-volume-up" data-sound={{question.voiceLink}}></div>' +
			'</div>' +
		'{{/if}}' +
		'<div class="col-md-offset-2 col-md-4">' +
			'<div class="question-image">' + 
				'<img src="{{question.pictureLink}}">' +
			'</div>' +
			'<div class="question-context">{{question.context}}</div>' +
		'</div>' +
		'<button class="forward btn col-md-offset-2 {{#if symbols.length}}btn-default{{else}}btn-primary{{/if}}">forward</button>' +
	'</div>';
});