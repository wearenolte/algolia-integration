/**
 * Init Instant Search widgets.
 *
 * The search box widget can only be linked to one index. So to add other indexes, a search function should be set.
 * In this search function, one post type is set as the main search while the others, if available, are the secondary searches linked to the main search.
 *
 * Ref: https://jsfiddle.net/j9nwpz34/27/
 *
 * @param {object} postTypes
 */
(function initInstantSearch( postTypes ) {
  if ( postTypes.length === 0 ) {
    return;
  }

  /**
   * Return the main search Post Type key which will be the first element.
   *
   * @returns {string}
   */
  function getMainSearchPostTypeKey() {
    return postTypes[0];
  }

  /**
   * Return an array with the post types except the main one.
   *
   * @returns {array}
   */
  function getSecondarySearchesKeys() {
    return postTypes.slice(1);
  }

  /**
   * Sets all the secondary index searches.
   *
   * @param {object} helper
   * @param {object} query
   * @param {array} secondarySearches
   */
  function setSecondarySearches( helper, query, secondarySearches ) {
    for ( index = 0; index < secondarySearches.length; ++index ) {
      secondarySearches[ index ].helper.setQuery( query );
      secondarySearches[ index ].helper.search();
    }

    helper.search();
  }

  /**
   * Init Main Search for main Post Type.
   *
   * @param {array} secondarySearches The build search function.
   */
  function buildMainSearch( secondarySearches ) {
    var mainSearchKey = getMainSearchPostTypeKey();

    var mainSearch = window.instantsearch({
      appId: window.algolia.app_id,
      apiKey: window.algolia.search_key,
      indexName: mainSearchKey,
      routine: true,
      searchFunction: function( helper ) {
        var query = mainSearch.helper.state.query;
        setSecondarySearches( helper, query, secondarySearches );
      },
      searchParameters: {
        hitsPerPage: 3
      }
    });

    var mainSearchHitsWidget = window.instantsearch.widgets.hits({
      container: '#hits-' + mainSearchKey,
      templates: {
        empty: 'No results',
        item: window.algolia.hits_item_template
      }
    });

    mainSearch.addWidget( mainSearchHitsWidget );

    return mainSearch;
  }

  /**
   * Init the secondary Search and widgets.
   *
   * @return {array}
   */
  function buildSecondarySearches() {
    var secondarySearches = getSecondarySearchesKeys();
    var searchWidgets = [];

    for ( index = 0; index < secondarySearches.length; ++index ) {
      var postTypeSlug = secondarySearches[ index ];

      var search = window.instantsearch({
        appId: window.algolia.app_id,
        apiKey: window.algolia.search_key,
        indexName: postTypeSlug,
        routine: true,
        searchParameters: {
          hitsPerPage: 3
        }
      });

      var hitsWidget = window.instantsearch.widgets.hits({
        container: '#hits-' + postTypeSlug,
        templates: {
          empty: 'No results',
          item: window.algolia.hits_item_template
        }
      });

      search.addWidget( hitsWidget );
      search.start();

      searchWidgets.push( search )
    }

    return searchWidgets;
  }

  var secondarySearches = buildSecondarySearches();

  var mainSearch = buildMainSearch( secondarySearches );

  var searchBox = window.instantsearch.widgets.searchBox({
    container: '#searchbox',
    placeholder: 'Search'
  });

  mainSearch.addWidget( searchBox );
  mainSearch.start();
})( window.algolia.post_types );
