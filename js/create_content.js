/**
 * init
 */
function init() {
  if($('#CreateContentForm_type').val() == 'text') {
	$('#CreateContentForm_player_link').closest('.row').hide();
  }
}

function bind_events() {
  $('#CreateContentForm_type').change(function() {
    if($(this).val() == 'text') {
      $('#CreateContentForm_player_link').closest('.row').hide();
    } else {
      $('#CreateContentForm_player_link').closest('.row').show();
    }

    return false;
  });
}

$(document).ready(function() {
  init();
  bind_events();
});