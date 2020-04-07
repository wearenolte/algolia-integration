<?php

namespace AlgoliaIntegration;

use Algolia\AlgoliaSearch\SearchIndex;

/**
 * Class PostSync
 *
 * Syncs posts in Algolia.
 *
 * @package AlgoliaIntegration
 */
class PostSync {
	/**
	 * The Algolia index.
	 *
	 * @var SearchIndex
	 */
	private $index;

	/**
	 * The record array structure to sync.
	 *
	 * @var array
	 */
	private $record_format;

	/**
	 * PostSync constructor.
	 *
	 * @param string      $post_type     The Post type to sync.
	 * @param SearchIndex $index         The Algolia index.
	 * @param array       $record_format The record array structure to sync.
	 */
	public function __construct( string $post_type, SearchIndex $index, array $record_format ) {
		$this->index         = $index;
		$this->record_format = $record_format;

		add_action( 'save_post_' . $post_type, [ $this, 'update_post' ], 10, 2 );
	}

	/**
	 * Adds or updates a post record.
	 *
	 * @param int      $post_id  The post ID.
	 * @param \WP_Post $the_post The post object.
	 */
	public function update_post( int $post_id, \WP_Post $the_post ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		$record = self::format_post( $the_post );

		if ( ! isset( $record['objectID'] ) ) {
			$record['objectID'] = implode( '#', [ $the_post->post_type, $the_post->ID ] );
		}

		if ( 'trash' === $the_post->post_status ) {
			$this->index->deleteObject( $record['objectID'] );

			return;
		}

		$this->index->saveObject( $record );
	}

	/**
	 * Returns a Post's data in a format ready to be saved in Algolia.
	 *
	 * @param \WP_Post $the_post The post object.
	 *
	 * @return array
	 */
	public function format_post( \WP_Post $the_post ) {
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

		return wp_parse_args(
			apply_filters( 'algolia_integration_format_' . $the_post->post_type, [], $the_post->ID ),
			[
				'objectID'           => implode( '#', [ $the_post->post_type, $the_post->ID ] ),
				'title'              => $the_post->post_title,
				'author'             => [
					'id'   => $the_post->post_author,
					'name' => get_user_by( 'ID', $the_post->post_author )->display_name,
				],
				'excerpt'            => $the_post->post_excerpt,
				'content'            => wp_strip_all_tags( $the_post->post_content ),
				'tags'               => $tags,
				'categories'         => $categories,
				'url'                => get_post_permalink( $the_post->ID ),
				'featured_image_url' => get_the_post_thumbnail_url( $the_post->ID ),
			]
		);
	}
}
