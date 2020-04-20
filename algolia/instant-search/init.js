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
   * Checks if the widget config options contains the method "container".
   * If it doesn't, then add the container method with the default value (templateDOMId).
   *
   * @param {object} config The Hits widget config options.
   * @param {string} templateDOMId The widget template key.
   * @returns {object}
   */
  function updateWidgetTemplate( config, templateDOMId ) {
    var clonedConfig = JSON.parse( JSON.stringify( config ) );

    if ( ! config.hasOwnProperty( 'container' ) ) {
      clonedConfig.container = templateDOMId;
    }

    return clonedConfig;
  }

  /**
   * Merges the Search Instance configuration with the custom config from the
   * user.
   *
   * The methods appID, apiKey, indexName can't be overwritten by the user.
   *
   * @param config
   * @param index
   * @returns {any}
   */
  function updateSearchConfig( config, index ) {
    var clonedConfig = JSON.parse( JSON.stringify( config ) );

    clonedConfig.appId = window.algolia.app_id;
    clonedConfig.apiKey = window.algolia.search_key;
    clonedConfig.indexName = index;

    return clonedConfig;
  }

  /**
   * Merges the Search Instance configuration with the custom config from the
   * user.
   *
   * If the method searchFunction is not present in the user config then use the default one.
   *
   * @param config
   * @param index
   * @returns {any}
   */
  function updateMainSearchConfig( config, index ) {
    var clonedConfig = updateSearchConfig( config, index );

    if ( ! config.hasOwnProperty( 'searchFunction' ) ) {
      clonedConfig.searchFunction = function( helper ) {
        setSecondarySearches( helper, mainSearch.helper.state.query, secondarySearches );
      };
    }

    return clonedConfig;
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
   * Init the secondary Search and widgets.
   *
   * @param {bool} print_hits_widget Create the Hits widgets for the secondary searches?
   * @return {array}
   */
  function buildSecondarySearches( print_hits_widget ) {
    var secondarySearches = getSecondarySearchesKeys();
    var searchWidgets = [];

    for ( index = 0; index < secondarySearches.length; ++index ) {
      var postTypeSlug = secondarySearches[ index ];

      var search = window.instantsearch(
        updateSearchConfig( window.algolia.secondary_search_config, postTypeSlug )
      );

      var hitsWidget = window.instantsearch.widgets.hits(
        updateWidgetTemplate(
          window.algolia.hits_config,
          '.algolia-hits-' + postTypeSlug
        )
      );

      search.addWidget( hitsWidget );
      search.start();

      searchWidgets.push( search );
    }

    return searchWidgets;
  }

  var secondarySearches = buildSecondarySearches( window.print_algolia_hits_widget );

  var mainSearchKey = getMainSearchPostTypeKey();

  var mainSearch = window.instantsearch(
    updateMainSearchConfig( window.algolia.main_search_config, mainSearchKey )
  );

  if ( window.print_algolia_search_box_widget ) {
    var searchBox = window.instantsearch.widgets.searchBox(
      updateWidgetTemplate(
        window.algolia.search_box_config,
        '.algolia-searchbox'
      )
    );

    mainSearch.addWidget( searchBox );
  }

  if ( window.print_algolia_hits_widget ) {
    var mainSearchHitsWidget = window.instantsearch.widgets.hits(
      updateWidgetTemplate(
        window.algolia.hits_config,
        '.algolia-hits-' + mainSearchKey
      )
    );

    mainSearch.addWidget( mainSearchHitsWidget );
  }

  mainSearch.start();
})( window.algolia.post_types );
