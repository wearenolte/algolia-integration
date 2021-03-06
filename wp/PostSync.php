<?php

namespace AlgoliaIntegration\wp;

use Algolia\AlgoliaSearch\SearchIndex;
use AlgoliaIntegration\algolia\Client;
use AlgoliaIntegration\algolia\Record;

/**
 * Class PostSync
 *
 * Syncs posts in Algolia on Post Save event.
 *
 * @package AlgoliaIntegration
 */
class PostSync {
	/**
	 * The Algolia client.
	 *
	 * @since 1.0.0
	 * @var $index
	 */
	private $client;

	/**
	 * The post type.
	 *
	 * @since 1.0.0
	 * @var $post_type
	 */
	private $post_type;

	/**
	 * An Algolia record..
	 *
	 * @since 1.0.0
	 * @var Record $record
	 */
	private $record;

	/**
	 * PostSync constructor.
	 *
	 * @param string $post_type The Post type to sync.
	 * @param Client $client    The Algolia client wrapper.
	 *
	 * @since 1.0.0
	 */
	public function __construct( string $post_type, Client $client ) {
		$this->client    = $client;
		$this->post_type = $post_type;

		$this->record = new Record( $client->get_index( $post_type ) );

		add_action( 'save_post_' . $post_type, [ $this, 'update_post' ], 10, 2 );
	}

	/**
	 * Set the default searchable fields.
	 *
	 * @since 1.0.0
	 */
	public function set_default_searchable_fields() {
		$default_index_settings = [
			'searchableAttributes' => [ 'title' ],
		];

		$index_settings = apply_filters(
			'algolia_integration_index_settings_' . $this->post_type,
			$default_index_settings
		);

		$this->record->set_searchable_attributes( $index_settings );
	}

	/**
	 * Adds, removes or updates a post record.
	 *
	 * @param int      $post_id  The post ID.
	 * @param \WP_Post $the_post The post object.
	 *
	 * @since 1.0.0
	 */
	public function update_post( int $post_id, \WP_Post $the_post ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$record = self::format_post( $the_post );

		if ( ! isset( $record['objectID'] ) ) {
			$record['objectID'] = implode( '#', [ $the_post->post_type, $the_post->ID ] );
		}

		if ( 'publish' !== $the_post->post_status ) {
			$this->record->delete( $record['objectID'] );

			return;
		}

		$this->record->save( $record );
	}

	/**
	 * Returns a Post's data in a format ready to be saved in Algolia.
	 *
	 * @param \WP_Post $the_post The post object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function format_post( \WP_Post $the_post ): array {
		$tags = array_map(
			function ( \WP_Term $term ) {
				return $term->name;
			},
			wp_get_post_terms( $the_post->ID, 'post_tag' )
		);

		$categories = array_map(
			function ( \WP_Term $term ) {
				return $term->name;
			},
			wp_get_post_terms( $the_post->ID, 'category' )
		);

		$default_fields = [
			'objectID'                 => implode( '#', [ $the_post->post_type, $the_post->ID ] ),
			'title'                    => $the_post->post_title,
			'published_date'           => get_the_date( 'Y-m-d', $the_post->ID ),
			'published_date_timestamp' => get_post_time( 'U', false, $the_post->ID ),
			'author'                   => [
				'id'   => $the_post->post_author,
				'name' => get_user_by( 'ID', $the_post->post_author )->display_name,
			],
			'excerpt'                  => $the_post->post_excerpt,
			'content'                  => wp_strip_all_tags( $the_post->post_content ),
			'tags'                     => $tags,
			'categories'               => $categories,
			'url'                      => get_post_permalink( $the_post->ID ),
			'featured_image_url'       => get_the_post_thumbnail_url( $the_post->ID ),
		];

		return apply_filters( 'algolia_integration_format_' . $the_post->post_type, $default_fields, $the_post->ID );
	}
}
