<?php

namespace AlgoliaIntegration\wp;

/**
 * Class Options.
 *
 * Class to store the keys from the Options API (DB store).
 *
 * @package AlgoliaIntegration\wp
 */
class OptionsApiKeys {
	/**
	 * The APP ID option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_APP_ID = 'algolia_integration_app_id';

	/**
	 * The Admin API key option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_ADMIN_API_KEY = 'algolia_integration_admin_api_key';

	/**
	 * The Search-Only API Key option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_SEARCH_ONLY_KEY = 'algolia_integration_search_only_key';

	/**
	 * The test APP ID option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_APP_ID_TEST = 'algolia_integration_app_id_test';

	/**
	 * The test Admin API key option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_ADMIN_API_KEY_TEST = 'algolia_integration_admin_api_key_test';

	/**
	 * The test Search-Only API Key option key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_SEARCH_ONLY_KEY_TEST = 'algolia_integration_search_only_key_test';

	/**
	 * The post types options key.
	 *
	 * @since 1.0.0
	 */
	const FIELD_POST_TYPES = 'algolia_integration_post_types';

	/**
	 * Returns a list of the options keys.
	 *
	 * @return array
	 */
	public static function get_options_keys() : array {
		$temp_class = new \ReflectionClass( __CLASS__ );
		return $temp_class->getConstants();
	}
}
