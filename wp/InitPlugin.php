<?php

namespace AlgoliaIntegration\wp;

use AlgoliaIntegration\algolia\Client;
use AlgoliaIntegration\wp\dashboard\SettingsPage;

/**
 * Class InitPlugin
 *
 * @package AlgoliaIntegration\wp
 */
class InitPlugin {
	/**
	 * The Algolia Client Wrapper.
	 *
	 * @since 1.0.0
	 * @var Client $algolia_client
	 */
	private $algolia_client;

	/**
	 * The Algolia App ID.
	 *
	 * @since 1.0.0
	 * @var string $app_id
	 */
	private $app_id;

	/**
	 * The Algolia Admin api key.
	 *
	 * @since 1.0.0
	 * @var string $admin_api_key
	 */
	private $admin_api_key;

	/**
	 * The Algolia Search-only key.
	 *
	 * @since 1.0.0
	 * @var string $search_key
	 */
	private $search_key;

	/**
	 * The post types to sync.
	 *
	 * @since 1.0.0
	 * @var array $post_types
	 */
	private $post_types;

	/**
	 * The plugin name.
	 *
	 * @since 1.0.0
	 */
	const PLUGIN_NAME = 'algolia-integration';

	/**
	 * InitPlugin constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init' ], 20 );
		register_deactivation_hook( plugin_basename( ALGOLIA_INTEGRATION_PLUGIN_FILE ), [ $this, 'deactivate' ] );
	}

	/**
	 * Load Settings page and Algolia client and set posts types to sync.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Get options data.
		$this->app_id = (string) get_option(
			WP_DEBUG
				? OptionsApiKeys::FIELD_APP_ID_TEST
				: OptionsApiKeys::FIELD_APP_ID
		);

		$this->search_key = (string) get_option(
			WP_DEBUG
				? OptionsApiKeys::FIELD_SEARCH_ONLY_KEY_TEST
				: OptionsApiKeys::FIELD_SEARCH_ONLY_KEY
		);

		$this->admin_api_key = (string) get_option(
			WP_DEBUG
				? OptionsApiKeys::FIELD_ADMIN_API_KEY_TEST
				: OptionsApiKeys::FIELD_ADMIN_API_KEY
		);

		$this->post_types = (array) get_option( OptionsApiKeys::FIELD_POST_TYPES );

		// Init Settings page.
		new SettingsPage( self::PLUGIN_NAME );

		// Exit if required keys are empty.
		if ( ! $this->app_id || ! $this->admin_api_key ) {
			return;
		}

		$this->algolia_client = new Client( $this->app_id, $this->admin_api_key );

		$post_types = new PostTypes( $this->algolia_client, $this->post_types );

		// Init Instant Search.
		new LoadAssets(
			[
				'app_id'             => $this->app_id,
				'search_key'         => $this->search_key,
				'post_types'         => $post_types->get_synced_post_types(),
				'hits_item_template' => apply_filters(
					'algolia_integration_hits_template',
					'<a href="{{{url}}}">{{{_highlightResult.title.value}}}</a>'
				),
			]
		);

		// Init shortcodes.
		new Shortcodes( $this->post_types );

		// Init Post types sync.
		foreach ( array_keys( $this->post_types ) as $post_type_slug ) {
			$post_sync = new PostSync( $post_type_slug, $this->algolia_client );
			$post_sync->set_default_searchable_fields();
		}
	}

	/**
	 * Delete options in DB.
	 *
	 * @since 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.StaticAccess)
	 */
	public static function deactivate() {
		foreach ( OptionsApiKeys::get_options_keys() as $option_key ) {
			delete_option( $option_key );
		}
	}
}
