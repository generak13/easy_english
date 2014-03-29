var current_page = 1;
/**
 * bind events
 */
function bindEvents() {
  var current_word_serach = $('#word_search_textfield').val();
  
  $('.sound-icon').click(function(event) {
    var audio = new Audio();
    audio.src = $(this).data('sound');
    audio.setAttribute('type', 'audio/mp3'); 
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
	.on('click', 'span.dictionary-item-delete', function() {
	  delete_dictionary_record($(this));
  })
	.on('click', 'span.dictionary-item-edit', function() {
	  EditDictionaryItem($(this));
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
          $('.dictionary-search-results').prepend('<div class="dictionary-search-results-item">' + response.data[i].text + '</div><img style="float: right; height: 5px;" src="' + response.data[i].image + '">');
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

function EditDictionaryItem(elem) {
  var id = $(elem).closest('div.dictionary-item').find('.dictionary-item-phrase').data('word');
  var editData = null;

  $.ajax({
	url: '/dictionary/loadWordData',
	type: 'GET',
	dataType: 'json',
	data: {
	  id: id
	}
  }).done(function (data, status) {
	editData = data;
	onSuccess();
  });
  
  function onSuccess () {
	editData.selected = editData.data[0].translation_id;
	renderDialogData();
	initDialog();
	initDialogBindings();
  }
  
  function initDialogBindings() {
	$('#edit-dictionary-dialog')
	  .on('hide.bs.modal', removeDialogBindings)
	  .on('blur', '.change-image', function() {
		if ($(this).val()) {
		  $('.translation-image img', '#edit-dictionary-dialog').attr('src', $(this).val());
		}
		return false;
	})
	  .on('blur', '.change-context', function() {
		if ($(this).val()) {
		  $('.context', '#edit-dictionary-dialog').text($(this).val());
		}
		return false;
	})
	  .on('click', '.translations-list-item', function() {
		editData.selected = '' + $(this).data()['id'];
		renderDialogData();
	})
	  .on('click', '.save', function() {
		var selectedItem = getSelectedDataItem(editData.selected),
			image_url = $('.change-image', '#edit-dictionary-dialog').val() || selectedItem.image_url,
			context = $('.change-context', '#edit-dictionary-dialog').val() || selectedItem.context;
		$.ajax({
		  url: '/dictionary/saveDictionaryTranslation',
		  type: 'GET',
		  data: {
			dictionary_id: selectedItem.dictionary_id,
			image_url: image_url,
			context: context
		  }
		  
		}).done(function () {
		  $.extend(selectedItem, {
			image_url: image_url,
			context: context
		  });
		});
	})
	  .on('click', '.remove', function() {
		var id = $(this).parent().data().id;
		var selectedItem = getSelectedDataItem(id);
		if (editData.data.length > 1) {
		  $.ajax({
			url: '/dictionary/deleteDictionaryTranslation',
			type: 'GET',
			data: {
			  id: selectedItem.dictionary_id
			}
		  }).done(function() {
			editData.data.slice(editData.data.indexOf(selectedItem), 1);
			renderDialogData();
			$.jnotify("Translation was deleted");
		  });
		} else {
		  $.jnotify("You cannot remove last translation in such way", "warning");
		}
	  });
  }
  
  function getSelectedDataItem(id) {
	for(var i = editData.data.length - 1; i >= 0; i--) {
	  if (editData.data[i].translation_id == id) {
		return editData.data[i];
	  }
	}
  }
  
  function removeDialogBindings() {
	$('#edit-dictionary-dialog')
	  .off('hide.bs.modal')
	  .off('blur', '.change-image')
	  .off('blur', '.change-context')
	  .off('click', '.translations-list-item')
	  .off('click', '.save')
	  .off('click', '.remove');
  }
  
  function renderDialogData() {
	var source   = $("#edit-dictionary-template").html();
	var template = Handlebars.compile(source);
	$('#edit-dictionary-dialog .modal-body').html(template(editData));
  }
  
  function initDialog() {
	$('#edit-dictionary-dialog').modal();
  }
  
  Handlebars.registerHelper('ifEqual', function(v1, v2, options) {
	if(v1 === v2) {
	  return options.fn(this);
	}
	return options.inverse(this);
  });
}

function saveEditedDictionaryRecord() {
  
}

$(document).ready(function() {
  bindEvents();
});