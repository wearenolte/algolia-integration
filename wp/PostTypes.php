<?php

namespace AlgoliaIntegration\wp;

use AlgoliaIntegration\algolia\Client;

/**
 * Class PostTypes.
 *
 * Handles the Post Types to sync.
 *
 * @package AlgoliaIntegration\wp
 */
class PostTypes {
	/**
	 * The Algolia client wrapper.
	 *
	 * @var Client $client
	 */
	private $client;

	/**
	 * The post types.
	 *
	 * @var array
	 */
	private $post_types;

	/**
	 * PostTypes constructor.
	 *
	 * @since 1.0.0
	 * @param Client $client     The Algolia client wrapper.
	 * @param array  $post_types A list of the post types.
	 */
	public function __construct( Client $client, array $post_types ) {
		$this->post_types = $post_types;
		$this->client     = $client;
	}

	/**
	 * Return a list of the post types slugs that are created as indexes in Algolia.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_synced_post_types() : array {
		$post_types = array_keys( $this->post_types );

		$indexes = $this->client->get_index_list();

		return array_intersect( $post_types, $indexes );
	}
}
