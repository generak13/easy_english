var currentPage = 1;
/**
 * bind events
 */
function bind_events() {
  $(document).
	on('click', '.pagination li', function() {
	  if(!$(this).hasClass('disabled')) {
		refreshTranslationsList($(this).data('page'));
	  }
	  
	  return false;
  }).
	on('change', '.translation-block .approve-disapprove', function() {
	  appoveDissaproveTranslation($(this).closest('.translation-block').data('translation'), $(this).is(':checked'));
	  return false; 
  }).
	on('click', '.translation-block .edit-translation', function() {
	  editTranslation($(this).closest('.translation-block'));
	  return false;
  }).
	on('click', '.translation-block .remove-translation', function() {
	  removeTranslation($(this).closest('.translation-block').data('translation'));
	  return false;
  });
  
  $('#translation_search_textfield').keypress(function(e) {
	if(e.keyCode == 13) {
	  refreshTranslationsList();
    }
  });
  
  $('#translation_search_button').click(function() {
	refreshTranslationsList();
	return false;
  });
  
  $('#show-all').change(function() {
	refreshTranslationsList();
	return false;
  });
}

/**
 * refresh translations list
 */
function refreshTranslationsList(selectedPage) {
  var page = selectedPage || 1;
  var text = $('#translation_search_textfield').val();
  var showAll = $('#show-all').is(':checked');
  
  $.getJSON(
	'/dictionary/getTranslationsList',
	{
	  page: page,
	  text: text,
	  showAll: showAll
	},
	function(response) {
	  if(response.success) {
		current_page = response.page;
		$('#translation-list').html(response.content);
	  } else {
		if(response.msg) {
		  $.jnotify(response.msg, "error");
		} else {
		  $.jnotify("Sorry, but some errors occured while receiving translations list", "error");
		}
	  }
	  
	}
  );
}

/**
 * Comment
 */
function appoveDissaproveTranslation(id, setVerified) {
  $.getJSON(
	'/dictionary/appoveDissaproveTranslation',
	{
	  id: id,
	  verified: setVerified
	},
	function(response) {
	  if(response.success) {
		var msg = setVerified ? 'Approved' : 'Set for verification';
		
		$.jnotify(msg);
	  } else {
		if(response.msg) {
		  $.jnotify(response.msg, 'error');  
		} else {
		  $.jnotify("Sorry, but some errors occured while receiving translations list", 'error');  
		}
	  }
	}
  );
}

function editTranslation(translatonBlock) {
  $('#edit-translation-dialog .translation').val($(translatonBlock).find('.translation-text').text());
  $('#edit-translation-dialog .translation').data('id', $(translatonBlock).data('translation'));
  $('#edit-translation-dialog .word').val($(translatonBlock).find('.word-text').text());
  
  var buttons = [{
	text: 'Save',
	click: function() {
	  saveTranslation($(this).find('.translation').data('id'), $(this).find('.translation').val());
	}
  }, {
	text: 'Close',
	click: function() {
	  $(this).dialog("close");
	}
  }];
  
  $('#edit-translation-dialog').dialog();
  $('#edit-translation-dialog').dialog({
	position: 'center',
	modal: true,
	resizable: false,
	buttons: buttons
  });
  
  $('#edit-translation-dialog').dialog('open');
}

function saveTranslation(id, text) {
  $.getJSON(
	'/dictionary/saveTranslation',
	{
	  id: id,
	  text: text
	},
	function(response) {
	  if(response.success) {
		$('#edit-translation-dialog').dialog('close');
		$.jnotify('Translation was saved');
	  } else {
		if(response.msg) {
		  $.jnotify(response.msg, 'error');  
		} else {
		  $.jnotify("Sorry, but some errors occured while saving translations", 'error');
		}
	  }
	}
  );
}

function removeTranslation(id) {
  $.getJSON(
	'/dictionary/removeTranslation',
	{
	  id: id
	},
	function(response) {
	  if(response.success) {
		$.jnotify('Translation has been deleted');
		refreshTranslationsList(currentPage);
	  } else {
		if(response.msg) {
		  $.jnotify(msg, 'error');
		} else {
		  $.jnotify('Internal error occured', 'error');
		}
	  }
	}
  );
}

$(document).ready(function() {
  bind_events();
});