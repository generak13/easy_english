var current_page = 1;

function bind_events() {
  $('div.user-item-role select').change(function() {
	var user_id = $(this).closest('div.user').data('user');
	var role = $(this).find('option:selected').val();
	
	changeUserRole(user_id, role);
  });
  
  $('#users_search_textfield').keypress(function(e) {
	if(e.keyCode == 13) {
	  refresh_users_list(1);
	}
  });
  
  $('#users_search_button').click(function() {
	refresh_users_list(1);
  });
  
  $(document)
  .on('click', '.pagination li', function() {
	if(!$(this).hasClass('disabled')) {
	  var page = current_page;
	  refresh_users_list($(this).data('page'));
	}

	return false;
  });
}

/**
 * refresh users list
 */
function refresh_users_list(page) {
  var params = {};
  params.text = $('#users_search_textfield').val();
  params.page = page || 1;

  $.getJSON(
    "/manageUsers/getUsers",
    params,
    function(response) {
      if(response.success) {
        current_page = response.page;
        $('#users-list').html(response.content);
      }
    }
  );
}

/**
 * change user role
 */
function changeUserRole(user_id, role) {
  $.getJSON(
	'/manageUsers/changeRole',
	{
	  user_id: user_id,
	  role: role
	},
	function(response) {
	  if(response.success) {
		$.jnotify("User's role was changed");
	  } else {
		if(response.msg) {
		  $.jnotify(response.msg, "error");
		} else {
		  $.jnotify("Sorry, but some errors occured while changing user's role", "error");
		}
	  }
	}
  );
}

$(document).ready(function() {
  bind_events();
});