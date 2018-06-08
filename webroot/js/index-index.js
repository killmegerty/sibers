$(document).ready(() => {
  // replace text in dropdown button after select menu item
  $('#search-engine-dropdown-menu').on('click', 'a', function() {
    let engineId = $(this).attr('data-engine-id');
    $('#search-engine-dropdown').text($(this).text()).attr('data-engine-id', engineId);
  });

  $('#search-query').on('keypress', function(e) {
    if (e.keyCode == 13) {
      $('#search-btn').click();
    }
  });

  $('#search-btn').on('click', function() {
    let searchQuery = $('#search-query').val();
    let engineId = $('#search-engine-dropdown').attr('data-engine-id');
    let searchResultsEl = $('#search-results');
    let loaderEl = $('.loader');

    if (searchQuery.length == 0) {
      return;
    }

    $.ajax({
      url: '/index/ajaxSearch',
      method: 'POST',
      data: {
        'query': searchQuery,
        'engineId': engineId
      },
      beforeSend: function() {
        loaderEl.toggleClass('hidden');
        searchResultsEl.empty();
      },
      success: function(data) {
        loaderEl.toggleClass('hidden');
        searchResultsEl.html(data);
      },
      error: function(e) {
        console.log('error occurred', e);
      }
    });
  });

})
