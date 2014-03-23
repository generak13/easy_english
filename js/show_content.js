current_context = null;
current_word = null;
searched_word = null;
timer = null;
/**
 * bind events
 */
function bind_events() {
  //set content as learned
  $('#set-learned').click(function() {
    set_content_learned();
    return false;
  });
  
  $('.content-text')
	.on('mouseover', 'context', function(e) {
	if(e.target == current_context || $(e.target).closest('context').get(0) == current_context) {
      return;
    }
    
    var target = e.target;
    if(target.nodeName == 'TRAN') {
      target = $(e.target).closest('context').get(0);
    }

    current_context = target;
    timer = setTimeout(load_translation_context, 1000);
  })
	.on('mouseleave', 'context', function(e) {
	  var to_elem = e.toElement;
	  if(to_elem == current_context || $(to_elem).closest('context').get(0) == current_context) {
		return;
	  }

	  if(to_elem == $('.dictionary-search-results').get(0) || to_elem == $('.dictionary-search-results-item').closest('div').get(0)) {
		return;
	  }

	  clearTimeout(timer);
	  $(current_context).removeClass('highlight-context');

	  if(current_word) {
		$(current_word).removeClass('highlight-tran');
	  }

	  current_context = null;
	  current_word = null;
  })
	.on('mouseleave', 'tran', function(e) {
	  $(e.target).removeClass('highlight-tran');
	  current_word = null;
  })
	.on('mouseover', 'tran', function(e) {
	  current_word = e.target;
	  $(current_word).addClass('highlight-tran');
  })
	.on('click', 'tran', function(e) {
	  searched_word = e.target;
    
	  var text = searched_word.innerHTML;
	  text = $.trim(text);

	  if(text) {
	   show_translation_suggestions(text);
	  }
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
      var context = $(current_context).text();
      add_word($(searched_word).text(), $(this).val(), context);
      
      $(searched_word).css('background-color', '');
      $(current_context).css('background-color', '');
    }
  });
  
  $(document)
	.on('click', '.pagination li', function() {
	  if(!$(this).hasClass('disabled')) {
		load_page($(this).data('page'));
	  }
	  
	  return false;
  });
}

/**
 * load page
 */
function load_page(page) {
  var id = $('.content').data('content'); 
  var params = {
	'id': id,
	'page': page || 1
  };
  
  $.getJSON(
    "/contentActions/show",
    params,
    function(response) {
      if(response.success) {
        $('.content-text').html(response.data.content);
        $('.pagination').replaceWith(response.data.pagination);
      }
    }
  );
}

/**
 * add new word with translation
 */
function add_word(word, translation, context) {
  var params = {};
  params.word_to_add = word;
  params.translation_for_word = translation;
  params.context = context;
  params.content_id = $('.content').data('content');
  
  $.getJSON(
    '/dictionary/add_word',
    params,
    function(response) {
      if(response.success) {
        $.jnotify("Word was added to your dictionary");
        
        $('.dictionary-search-results').hide();
        $('body').unbind('click');

        add_word_to_glossary_panel(params.word_to_add, params.translation_for_word);
        highlight_words(params.word_to_add);
      } else {
        $.jnotify("Sorry, but some errors occured while adding word to your dictionary", "error");
      }
    }
  );
}

/**
 * add word and translation to glossary panel
 */
function add_word_to_glossary_panel(word, translation) {
  var html_record =
    '<div>' + 
    '<div class="sound-icon" data-sound="/audio/' + word + '.mp3">' + 
      '<span class="glyphicon glyphicon-play-circle"></span>' +
    '</div>' + 
    '<span class="english-word">' + 
      '<a target="_blank" href="/dictionary/dictionary?text=' + word + '">' + word + '</a>' + 
    '</span> - <span class="translate">' + translation + '</span></div>';
    
  $('.words_wrapper').append(html_record);
  
  $('.sound-icon').unbind('click');
  $('.sound-icon').click(function(event) {
    var audio = new Audio();
    audio.src = $(this).data('sound');
    audio.play();

    event.preventDefault();
    return false;
  });
}

function show_translation_suggestions(text) {
  var params = {};
  params.text = text;

  $.getJSON(
    "/dictionary/get_translations",
    params,
    function(response) {
      if(response.success) {
        $(searched_word).data('translation', response.data);
        console.log(response.data);
        
        if(searched_word != current_word) {
          return; // TODO: don't show suggestion dialog
        }
        
        fill_suggestions(response.data);
      }
    }
  );
}

/**
 * fill block with suggestions
 */
function fill_suggestions(suggestions) {
  $('.dictionary-search-results .dictionary-search-results-item').remove();
        
  for(var i = suggestions.length - 1; i >= 0; i--) {
    $('.dictionary-search-results').prepend('<div class="dictionary-search-results-item">' + suggestions[i].text + '</div>');
  }

  $('.dictionary-search-results').find('input').val('');

  $('.dictionary-search-results').show();
  $('.dictionary-search-results').offset({left:$(searched_word).offset().left,top:$(searched_word).offset().top + $(searched_word).height()});
  
  $('body').click(function(e) {
    var selected_translation = $(e.target).closest('.dictionary-search-results-item');

    if(selected_translation.length != 0) {
      var context = $(current_context).text();
      add_word($(searched_word).text(), $(selected_translation).text(), context);
      
      $(searched_word).css('background-color', '');
      $(current_context).css('background-color', '');
    } else if($(e.target).closest('.dictionary-search-results').length != 0) {
    } else if($('.dictionary-search-results').is(':visible')) {
      $('.dictionary-search-results').hide();
      $('body').unbind('click');
    }
  });
}

/**
 * load translation for context
 */
function load_translation_context() {
  var context = current_context;
  
  if($(context).data('translation')) {
    console.log('Translation: ' + $(context).data('translation'));
    $('.sentense-translation').text($(context).data('translation'));
    highlight_context();
  } else {
    text = get_text(context);
    text = $.trim(text);
  
    if(text) {
      get_translation(text, context);
      highlight_context();
    }
  }
}

/**
 * get text for translaion
 */
function get_text(current_context) {
  var context_text = '';
  
  if(current_context) {
    var words_count = $(current_context).children().length;

    for(var i = 0; i < words_count; i++) {
      context_text += $(current_context).children()[i].innerText + ' ';
    }
  }
  
  return context_text;
}

/**
 * get translation by source text
 */
function get_translation(text, source) {
  $.getJSON(
    '/contentActions/get_sentence_translation',
    {text: text},
    function(response) {
      if(response.success) {
        $(source).data('translation', response.data);
        console.log('Translation: ' + response.data);
        $('.sentense-translation').text(response.data);
      } else {
        console.log(response.msg);
      }
    }
  );
}

/**
 * hightlight context
 */
function highlight_context() {
  if(current_context) {
    $(current_context).addClass('highlight-context');
  }
}

function fadelight_context() {
  if(current_context) {
    $(current_context).removeClass('highlight-context');
  }
  
  current_context = null;
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

/**
 * load words
 */
function load_learned_words() {
  var id = $('.content').data('content');
  
  $.getJSON(
    '/dictionary/get_learned_words_by_content',
    {content_id: id},
    function(response) {
      if(response.success) {
        console.log(response);
        for(var i = 0; i < response.data.length; i++) {
          var word = response.data[i]['word'];
          var translations = get_normalized_translations(response.data[i].translations);
          
          var dictionary_record = '<div>' + 
            '<span class="sound-icon" data-sound="' + response.data[i].sound + '">' +
              '<span class="glyphicon glyphicon-play-circle"></span>' +
            '</span>' +
            '<span class="english-word"><a target="_blank" href="/dictionary/dictionary?text=' + word + '">' + word + '</a>' +
            '</span> - <span class="translate">' + translations + '</span></div>';

          $('.words_wrapper').append(dictionary_record);
        }
        
        $('.sound-icon').unbind('click');
        $('.sound-icon').click(function(event) {
          var audio = new Audio();
          audio.src = $(this).data('sound');
          audio.play();

          event.preventDefault();
          return false;
        });
        
        highlight_dictionary(response.data);
      }
    }
  );
}

function highlight_dictionary(data) {
    for(var i = 0; i < data.length; i++) {
        highlight_words(data[i].word);
    }
}

/**
 * hi
 */
function highlight_words(text) {
    $('tran:contains("' + text + '")').addClass('in-dictionary');
}

function get_normalized_translations(translations) {
  var result = '';
  
  for(var i = 0; i < translations.length; i++) {
    result += translations[i];

    if(i != translations.length - 1) {
      result += '; ';
    }
  }
  
  return result;
}

$(document).ready(function() {
  bind_events();
  load_learned_words();
});