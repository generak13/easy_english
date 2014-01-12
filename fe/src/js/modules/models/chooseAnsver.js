define(['underscore', 'bb'], function ($,bb,_) {
	
	var ChooseAnsverModule = bb.extend({
		defaults: {
			question: {
				id: 1,
				phrase: '1',
				pictureLink: '1',
				voiceLink: '1'
			},
			answers: [ 
				{
					id: 1,
					phrase: '1'
				},
				{
					id: 2,
					phrase: '2'
				},
				{
					id: 3,
					phrase: '3'
				},
				{
					id: 4,
					phrase: '4'
				}
			]
		}
	});

	return  ChooseAnsverModule;
});