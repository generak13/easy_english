/**
 * bind events
 */
function bindEvents() {
  var current_word_serach = $('#word_search_textfield').val();
  
  $('.sound-icon').click(function(event) {
    var audio = new Audio();
    audio.src = $(this).data('sound');
    audio.play();

    event.preventDefault();
    return false;
  });

  $('#word_search_textfield').keyup(function(e) {
    //enter was pressed
    if (e.keyCode == 13) {
      if($(this).val() != current_word_serach) {
        refresh_dictionary();
        current_word_serach = $(this).val();
      }
    }
    
    e.preventDefault();
    return false;
  });
  
  $('#word_search_button').click(function(e) {
    if($('#word_search_textfield').val() != current_word_serach) {
      refresh_dictionary();
      current_word_serach = $('#word_search_textfield').val();
    }
    
    e.preventDefault();
    return false;
  });
  
  $('#add_word').click(function(e) {
    if($('#word_search_textfield').val()) {
      get_translations();
    }
    
    e.preventDefault();
    return false;
  });
}

/**
 * refresh dictionary list
 */
function refresh_dictionary() {
  var params = {};
  params.text = $('#word_search_textfield').val();

  $.getJSON(
    "/dictionary/get_dictionary",
    params,
    function(response) {
      if(response.success) {
        $('#dictionary_list').html(response.content);
      }
    }
  );
}

function get_translations() {
  var params = {};
  params.text = $('#word_search_textfield').val();

  $.getJSON(
    "/dictionary/get_translations",
    params,
    function(response) {
      if(response.success) {
        console.log(response.data);
      }
    }
  );
}

$(document).ready(function() {
  bindEvents();
});