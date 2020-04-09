<?php

namespace AlgoliaIntegration\src;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;

/**
 * Init Algolia Integration Main Class.
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class AlgoliaIntegration {
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
		$this->algolia_client = SearchClient::create( $app_id, $admin_api_key );
	}

	/**
	 * Returns the Algolia index.
	 *
	 * @since 1.0.0
	 * @param string $index The Algolia index name.
	 * @return \Algolia\AlgoliaSearch\SearchIndex
	 */
	public function get_index( string $index ) : SearchIndex {
		return $this->algolia_client->initIndex( $index );
	}
}
