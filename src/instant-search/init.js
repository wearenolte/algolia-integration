var search = window.instantsearch({
  appId: window.algolia.app_id,
  apiKey: window.algolia.search_key,
  indexName: 'Products',
  routine: true
});

search.addWidgets([
  window.instantsearch.widgets.searchBox({
    container: '#searchbox',
    placeholder: 'Search'
  }),

  window.instantsearch.widgets.hits({
    container: '#hits',
    templates: {
      empty: 'No results',
      item: '<em>Title</em>: {{{_highlightResult.title.value}}}'
    }
  })
]);

search.start();
