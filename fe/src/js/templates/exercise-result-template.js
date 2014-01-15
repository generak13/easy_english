define(function() {
	return '' + 
	'{{#each questions}}' +
		'{{#if question.correct}}' +
			'<div>question.phrase - true</div>' +
		'{{else}}' +
			'<div>question.phrase - false</div>' +
		'{{/if}}' +
	'{{/each}}' +
	'<button class="btn btn-primary repeat-exercise">Repeat</button>' +
	'<button class="btn btn-primary close-exercise">Close</button>';
});