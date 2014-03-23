/**
 * bind events
 */
function bind_events() {
  $('#change-login-email').click(function() {
	changeLoginEmail();
  });

  $('#change-password').click(function() {
	changePassword();
  });
}

function changeLoginEmail() {
  var login = $('#login').val();
  var email = $('#email').val();
  
  if(!validateEmail(email)) {
	$.jnotify("Email is not valid", "error");
	return;
  }
  
  $.getJSON(
	'/mySite/saveChanges',
	{
	  login: login,
	  email: email
	},
  function(response) {
	if (response.success) {
	  $.jnotify("Changes have been saved");
	} else {
	  if (response.msg) {
		$.jnotify(response.msg, "error");
	  } else {
		$.jnotify("Sorry, but some errors occured while saving changes", "error");
	  }
	}
  }
  );
}

function changePassword() {
  $.getJSON(
	'/mySite/changePassword',
	{
	  password: $('$password').val()
	},
  function(response) {
	if (response.success) {
	  $.jnotify("Password has been changed");
	} else {
	  if (response.msg) {
		$.jnotify(response.msg, "error");
	  } else {
		$.jnotify("Sorry, but some errors occured while changing password", "error");
	  }
	}
  }
  );
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function getWeekStatistics() {
  $.getJSON(
	'/mySite/getWeekStatistics',
	{},
	function(response) {
	  if(response.success) {
		var statistics = [],
		  data = response.data;
		statistics.push(['Date', 'You', "Norma"]);
		
		for(var i = 0; i < response.data.length; i++) {
		  statistics.push([data[i].date, data[i].points, data[i].norma_points]);
		}
		
		drawChart(statistics);
	  }
	}
  );
}

function drawChart(statistics) {
  var data = google.visualization.arrayToDataTable(statistics);

  var chart = new google.visualization.ImageLineChart($('.statistic-chart-container')[0]);
  chart.draw(data);
}

$(document).ready(function() {
  bind_events();
  
  google.setOnLoadCallback(getWeekStatistics);
});