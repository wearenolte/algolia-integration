<?php

namespace AlgoliaIntegration;

use Algolia\AlgoliaSearch\SearchClient;

/**
 * Init plugin.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AlgoliaIntegration {
	/**
	 * The Algolia client.
	 *
	 * @var $algolia_client
	 */
	private $algolia_client;

	/**
	 * AlgoliaIntegration constructor.
	 *
	 * @param string $app_id The Algolia app ID.
	 * @param string $admin_api_key The Algolia admin api key.
	 */
	public function __construct( string $app_id, string $admin_api_key ) {
		if ( $app_id && $admin_api_key ) {
			$this->algolia_client = SearchClient::create( $app_id, $admin_api_key );
		}
	}

	/**
	 * Call to sync an index with a post type.
	 *
	 * @param string $post_type The post type to sync.
	 * @param string $index_name Tha Algolia index name.
	 * @param array  $record_format The data to sync in array format.
	 */
	public function create_post_sync( string $post_type, string $index_name, array $record_format = [] ) {
		$index = $this->algolia_client->initIndex( $index_name );
		new PostSync( $post_type, $index, $record_format );
	}
}
