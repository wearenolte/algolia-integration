<?php

namespace AlgoliaIntegration\wp;

/**
 * Load Instant Search assets.
 *
 * Class LoadAssets.
 */
class LoadAssets {
	/**
	 * The data to be available in JS.
	 *
	 * @since 1.0.0
	 * @var array $js_vars
	 */
	private $js_vars;

	/**
	 * LoadAssets constructor.
	 *
	 * @param array $js_vars The data to be available in JS.
	 */
	public function __construct( array $js_vars ) {
		$this->js_vars = $js_vars;

		add_action( 'wp_enqueue_scripts', [ $this, 'load_instant_search_assets' ] );
	}

	/**
	 * Load Instant Search assets.
	 *
	 * @since 1.0.0
	 */
	public function load_instant_search_assets() {
		$js_path = ALGOLIA_INTEGRATION_PLUGIN_PATH . 'algolia/instant-search';

		// Enqueue Instant Search JS.
		if ( ! apply_filters( 'algolia_integration_disable_instant_search_js', false ) ) {
			wp_enqueue_script(
				'algolia-search',
				$js_path . '/instantsearch.js',
				[],
				'1.0.0',
				true
			);
		}

		// Declare Algolia App ID and Search key JS vars.
		wp_register_script(
			'algolia-js-vars',
			'',
			[],
			'1.0.0',
			true
		);

		wp_localize_script(
			'algolia-js-vars',
			'algolia',
			$this->js_vars
		);

		wp_enqueue_script( 'algolia-js-vars' );

		// Enqueue Instant Search Widget instantiation.
		if ( ! apply_filters( 'algolia_integration_disable_instant_search_custom_js', false ) ) {
			wp_enqueue_script(
				'algolia-instant-search-init',
				$js_path . '/init.js',
				[ 'algolia-search' ],
				'1.0.0',
				true
			);
		}

		// Enqueue Instant Search Widget CSS.
		if ( ! apply_filters( 'algolia_integration_disable_instant_search_css', false ) ) {
			wp_enqueue_style(
				'algolia-instantsearch',
				$js_path . '/instantsearch.min.css',
				[],
				'1.0.0'
			);

			wp_enqueue_style(
				'algolia-instantsearch-theme',
				$js_path . '/instantsearch-theme-algolia.min.css',
				[ 'algolia-instantsearch' ],
				'1.0.0'
			);
		}
	}
}
