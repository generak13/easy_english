var current_page = 1;
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
      show_translation_suggestions();
    }
    
    e.preventDefault();
    return false;
  });
  
  $('.add-your-translation').click(function() {
    if($('.your-translation').is(':visible')) {
      $('.your-translation').hide();
    } else {
      $('.your-translation').show();
    }
  });
  
  $('#custom_translation').keypress(function(e) {
    if(e.keyCode == 13) {
      add_word($('#word_search_textfield').val(), $(this).val());
    }
  });
  
  $(document)
	.on('click', '.pagination li', function() {
	  if(!$(this).hasClass('disabled')) {
		var page = current_page;
		refresh_dictionary($(this).data('page'));
	  }
	  
	  return false;
  })
	.on('click', 'div.dictionary-item-delete', function() {
	  delete_dictionary_record($(this));
  });
}

/**
 * refresh dictionary list
 */
function refresh_dictionary(page) {
  var params = {};
  params.text = $('#word_search_textfield').val();
  params.page = page || 1;

  $.getJSON(
    "/dictionary/get_dictionary",
    params,
    function(response) {
      if(response.success) {
        current_page = response.page;
        $('#dictionary_list').html(response.content);
      }
    }
  );
}

/**
 * add new word with translation
 */
function add_word(word, translation) {
  var params = {};
  params.word_to_add = word;
  params.translation_for_word = translation;
  
  $.getJSON(
    '/dictionary/add_word',
    params,
    function(response) {
      if(response.success) {
        $.jnotify("Word was added to your dictionary");
        
        $('.dictionary-search-results').hide();
        $('body').unbind('click');
      } else {
        $.jnotify("Sorry, but some errors occured while adding word to your dictionary", "error");
      }
    }
  );
}

function show_translation_suggestions() {
  var params = {};
  params.text = $('#word_search_textfield').val();

  $.getJSON(
    "/dictionary/get_translations",
    params,
    function(response) {
      if(response.success) {
        var suggestions_block =  $('.dictionary-search-results');
        $('.dictionary-search-results .dictionary-search-results-item').remove();
        
        for(var i = response.data.length - 1; i >= 0; i--) {
          $('.dictionary-search-results').prepend('<div class="dictionary-search-results-item">' + response.data[i] + '</div>');
        }
        
        $(suggestions_block).find('input').val('');
        
        $(suggestions_block).show();
        
        $('body').click(function(e) {
          var selected_translation = $(e.target).closest('.dictionary-search-results-item');
          
          if(selected_translation.length != 0) {
            add_word($('#word_search_textfield').val(), $(selected_translation).text());
          } else if($(e.target).closest('.dictionary-search-results').length != 0) {
          } else if($('.dictionary-search-results').is(':visible')) {
            $('.dictionary-search-results').hide();
            $('body').unbind('click');
          }
        });
      }
    }
  );
}

/**
 * delete_dictionary_record
 */
function delete_dictionary_record(elem) {
  var dictionary_record = $(elem).closest('div.dictionary-item').data('dictionary');
  
  $.getJSON(
    '/dictionary/remove_from_dictionary',
    {id: dictionary_record},
    function(response) {
        if(response.success) {
            $.jnotify("Word was successfully removed!");
            refresh_dictionary(current_page);
        } else {
            $.jnotify("Sorry, but some errors occured while removing word from your dictionary", "error");
        }
    }
  );
}

$(document).ready(function() {
  bindEvents();
});