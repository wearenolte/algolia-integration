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
	 * InitPlugin constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'init' ], 20 );
	}

	/**
	 * Load Settings page and Algolia client and set posts types to sync.
	 */
	public function init() {
		$settings = new SettingsPage( self::PLUGIN_NAME );

		$app_id        = get_option( WP_DEBUG ? $settings::FIELD_APP_ID_TEST : $settings::FIELD_APP_ID );
		$admin_api_key = get_option( WP_DEBUG ? $settings::FIELD_ADMIN_API_KEY_TEST : $settings::FIELD_ADMIN_API_KEY );

		if ( $app_id && $admin_api_key ) {
			self::$algolia = new AlgoliaIntegration( $app_id, $admin_api_key );

			$post_types = get_option( $settings::FIELD_POST_TYPES );

			foreach ( array_keys( $post_types ) as $post_type_slug ) {
				$post_type = get_post_type_object( $post_type_slug );
				new PostSync( $post_type_slug, self::$algolia->get_index( $post_type->labels->name ) );
			}
		}
	}
}
