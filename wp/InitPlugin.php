<?php

namespace AlgoliaIntegration\wp;

use AlgoliaIntegration\src\AlgoliaIntegration;
use AlgoliaIntegration\src\PostSync;

/**
 * Class InitPlugin
 *
 * @package AlgoliaIntegration\wp
 */
class InitPlugin {
	/**
	 * The Algolia integration init.
	 *
	 * @since 1.0.0
	 *
	 * @var AlgoliaIntegration
	 */
	private static $algolia;

	/**
	 * The plugin name.
	 *
	 * @since 1.0.0
	 */
	const PLUGIN_NAME = 'algolia-integration';

	/**
	 * The Algolia App ID.
	 *
	 * @var string $app_id The app id.
	 */
	private $app_id;

	/**
	 * The Algolia Search-only key.
	 *
	 * @var string $search_key The search-only key.
	 */
	private $search_key;

	/**
	 * InitPlugin constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_instant_search_assets' ] );
	}

	/**
	 * Load Settings page and Algolia client and set posts types to sync.
	 */
	public function init() {
		$settings = new SettingsPage( self::PLUGIN_NAME );

		$this->app_id     = get_option( WP_DEBUG ? $settings::FIELD_APP_ID_TEST : $settings::FIELD_APP_ID );
		$this->search_key = get_option( WP_DEBUG ? $settings::FIELD_SEARCH_ONLY_KEY_TEST : $settings::FIELD_SEARCH_ONLY_KEY );
		$admin_api_key    = get_option( WP_DEBUG ? $settings::FIELD_ADMIN_API_KEY_TEST : $settings::FIELD_ADMIN_API_KEY );

		if ( $this->app_id && $admin_api_key ) {
			self::$algolia = new AlgoliaIntegration( $this->app_id, $admin_api_key );

			$post_types = get_option( $settings::FIELD_POST_TYPES );

			foreach ( array_keys( $post_types ) as $post_type_slug ) {
				$post_type = get_post_type_object( $post_type_slug );
				$post_sync = new PostSync( $post_type_slug, self::$algolia->get_index( $post_type->labels->name ) );
				$post_sync->set_searchable_attributes();
			}
		}
	}

	/**
	 * Load Instant Search JS assets.
	 */
	public function load_instant_search_assets() {
		$js_path = ALGOLIA_INTEGRATION_PLUGIN_PATH . 'src/instant-search';

		// Enqueue Instant Search JS.
		wp_enqueue_script(
			'algolia-search',
			$js_path . '/instantsearch.js',
			[],
			'1.0.0',
			true
		);

		// Declare Algolia App ID and Search key JS vars.
		wp_register_script(
			'algolia-js-vars',
			'' ,
			[],
			'1.0.0',
			true
		);

		wp_localize_script(
			'algolia-js-vars',
			'algolia',
			[
				'app_id'     => $this->app_id,
				'search_key' => $this->search_key,
			]
		);

		wp_enqueue_script( 'algolia-js-vars' );

		// Enqueue Instant Search Widget instantiation.
		wp_enqueue_script(
			'algolia-instant-search-init',
			$js_path . '/init.js',
			[ 'algolia-search' ],
			'1.0.0',
			true
		);

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
