<?php

namespace AlgoliaIntegration\algolia;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;

/**
 * Init Algolia Client.
 *
 * Docs: https://www.algolia.com/doc/integration/wordpress/getting-started/quick-start/?language=php
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Client {
	/**
	 * The Algolia client.
	 *
	 * @since 1.0.0
	 * @var $algolia_client
	 */
	private $algolia_client;

	/**
	 * AlgoliaIntegration constructor.
	 *
	 * @since 1.0.0
	 * @param string $app_id The Algolia app ID.
	 * @param string $admin_api_key The Algolia admin api key.
	 */
	public function __construct( string $app_id, string $admin_api_key ) {
		try {
			$this->algolia_client = SearchClient::create( $app_id, $admin_api_key );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error initializing Algolia: ' . $e->getMessage() );
			}
		}
	}

	/**
	 * Returns the Algolia index.
	 *
	 * @since 1.0.0
	 * @param string $index The Algolia index name.
	 * @return \Algolia\AlgoliaSearch\SearchIndex
	 */
	public function get_index( string $index ) : SearchIndex {
		try {
			return $this->algolia_client->initIndex( $index );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error getting an index: ' . $e->getMessage() );
			}
		}
	}

	/**
	 * Returns the list of all indexes names.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_index_list() {
		try {
			$indexes = $this->algolia_client->listIndices();

			return array_map(
				function( $item ) {
					return $item['name'];
				},
				$indexes['items'] ?? []
			);
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error listing indexes: ' . $e->getMessage() );
			}
		}
	}
}
