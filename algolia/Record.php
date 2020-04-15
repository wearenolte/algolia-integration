<?php

namespace AlgoliaIntegration\algolia;

use Algolia\AlgoliaSearch\SearchIndex;

/**
 * Class Record
 *
 * Handles a record in Algolia.
 *
 * @package Record
 */
class Record {
	/**
	 * The Algolia index.
	 *
	 * @since 1.0.0
	 * @var $index
	 */
	private $index;

	/**
	 * PostSync constructor.
	 *
	 * @since 1.0.0
	 * @param SearchIndex $index The Algolia index.
	 */
	public function __construct( SearchIndex $index ) {
		$this->index = $index;
	}

	/**
	 * Save a record.
	 *
	 * @since 1.0.0
	 * @param array $record The data to save.
	 */
	public function save( array $record ) {
		try {
			$this->index->saveObject( $record );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error saving record in Algolia: ' . $e->getMessage() );
			}
		}
	}

	/**
	 * Deletes a record.
	 *
	 * @since 1.0.0
	 * @param string $record_id The record ID.
	 */
	public function delete( string $record_id ) {
		try {
			$this->index->deleteObject( $record_id );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error deleting record in Algolia: ' . $e->getMessage() );
			}
		}
	}

	/**
	 * Set the default searchable attributes of an index.
	 *
	 * Docs: https://www.algolia.com/doc/api-reference/api-methods/set-settings/
	 *
	 * @since 1.0.0
	 * @param array $index_settings The index settings.
	 */
	public function set_searchable_attributes( $index_settings ) {
		try {
			$this->index->setSettings( $index_settings );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				error_log( 'Error settings an index in Algolia: ' . $e->getMessage() );
			}
		}
	}
}
