/**
 * bind events
 */
function bind_events() {
  $('#set-learned').click(function() {
    debugger;
    set_content_learned();
    return false;
  });
}

/**
 * set content as learned
 */
function set_content_learned() {
  var id = $('.content').data('content'); 
  
  $.getJSON(
    '/contentActions/set_learned',
    {
      id: id
    },
    function(response) {
      if(response.success) {
        alert('You learned this content');
      }
    }
  );
}

$(document).ready(function() {
  bind_events();
});