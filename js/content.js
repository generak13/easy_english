/**
 * bind events for search
 */
function bindEvents() {
    $('#search-submit').click(function() {search_contents(); return false;});
    $('#search-lvl, #search-genre').on('multiselectclose' ,function() {search_contents()});
    $('#search-title').keypress(function(e) {
       if(e.keyCode == 13)  {
           search_contents();
       }
    });
    
    $('#search-type div').click(function() {
      $('#search-type div').removeClass('selected');
      $(this).addClass('selected');
      
      search_contents();
    });
    
    $('#content-status').click(function(e) {
        $('#content-status .selected').removeClass('selected');
        $(e.target).closest('li').addClass('selected');
        
        clear_filters();
        search_contents()
    });
}

/**
 * clear filter
 */
function clear_filters() {
    $('#search-title').val('');
    $('#search-lvl').val($('#search-lvl option:first').val());
    $('#search-genre').val($('#search-genre option:first').val());
}

function search_contents() {
    var params = {};
    
    params.title = $('#search-title').val();
    params.lvl = getLevels();
    params.genre = getGenres();
    params.type = $('#search-type div.selected').attr('type');
    params.content_status = $('.content-nav .selected').data('content-status');
    //    $('#content').mask('Loading...');
    
    $.getJSON(
        "/contentActions/get_contents",
        params,
        function(response) {
//            $('#content').unmask();
            
            if(response.success) {
               $('#grid') .html(response.content_table);
            }
        }
    );
}

/**
 * get genres
 */
function getGenres() {
 return $("#search-genre").multiselect("getChecked").map(function(){return this.value;}).get();
}

/**
 * get levels
 */
function getLevels() {
  return $("#search-lvl").multiselect("getChecked").map(function(){return this.value;}).get();
}

/**
 * register dropdown lists
 */
function registerDropDownLists() {
  $('#search-lvl').multiselect({
    selectedList: 3,
    noneSelectedText: 'Select Lvl'
  });
  $('#search-lvl').multiselect('checkAll');
  
  $('#search-genre').multiselect({
    selectedList: 3,
    noneSelectedText: 'Select Genre'
  });
  $('#search-genre').multiselect('checkAll');
  
  $('.ui-multiselect').css('width', '100%');
}

$(document).ready(function() {
  registerDropDownLists();
  bindEvents();
  search_contents();
});

