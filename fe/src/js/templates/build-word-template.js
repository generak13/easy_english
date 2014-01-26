define(function() {
	return '' + 
	'<div class="question">{{question.translation}}</div>' + 
	'<div class="question-voice"></div>' + 
	'<div class="question-image">' + 
		'<img src="{{question.pictureLink}}">' + 
	'</div>' + 
	'<div class="currentAnswer">' +
		'{{currentAnswer}}' +
	'</div>' +
	'<div class="symbols">{{#each symbols}}' +
		'<div class="symbol btn btn-default" data-symbol="{{this}}">' +
			'{{this}}' +
		'</div>' +
	'{{/each}}</div>' +
	'<button class="forward btn {{#if symbols.length}}btn-default{{else}}btn-primary{{/if}}">forward</button>';
});