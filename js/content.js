/**
 * bind events for search
 */
function bindEvents() {
    $('#search-submit').click(function() {search_contents(); return false;});
    $('#search-lvl, #search-genre').change(function() {search_contents()});
    $('#search-title').keypress(function(e) {
       if(e.keyCode == 13)  {
           search_contents();
       }
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
    params.lvl = $('#search-lvl').val();
    params.genre = $('#search-genre').val();
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

$(document).ready(function() {
    bindEvents();
    
    search_contents();
});

