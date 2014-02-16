define(['underscore', 'bb'], function (_,bb) {
	
	var ExercisesModule = bb.Model.extend({
		defaults: {
			exercises: [],
			type: 'list'
		},
		
		'fetch': function() {
      var data;
      
			$.ajax({
				url: '/exercises/getExcerciseList',
        async: false,
        dataType: 'JSON',
        type: "POST"
      }).done(function(response) {
        data = response;
      });
      return data;
		},
  
    'sendResults': function() {
      var obj = [];
      for (var i in this.get('questions')) {
        obj.push({
          id: this.get('questions')[i].question.id,
          correct: this.get('questions')[i].question.correct
        });
      }

      $.post(
        '/exercises/ProcessResults',
        {
          type: this.get('type'),
          results: obj
        }
      );
    }
	});

	return  ExercisesModule;
});
