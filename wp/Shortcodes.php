<?php

namespace AlgoliaIntegration\wp;

/**
 * Class Shortcodes
 *
 * @package AlgoliaIntegration\wp
 */
class Shortcodes {
	/**
	 * The post types list.
	 *
	 * @since 1.0.0
	 * @var array $post_types
	 */
	private $post_types;

	/**
	 * Init shortcodes.
	 *
	 * @since 1.0.0
	 * @param array $post_types The post types list.
	 */
	public function __construct( array $post_types ) {
		$this->post_types = $post_types;

		add_shortcode( 'print_algolia_search_box', [ $this, 'print_search_box' ] );
		add_shortcode( 'print_algolia_results', [ $this, 'print_results' ] );
	}

	/**
	 * Returns the div with ID linked with the Instant Search Search Box widget.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function print_search_box() {
		wp_localize_script(
			'algolia-js-vars',
			'print_algolia_search_box_widget',
			'true'
		);

		return '<div class="algolia-searchbox"></div>';
	}

	/**
	 * Returns the div with ID linked with the Instant Search Search Box widget.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function print_results() {
		wp_localize_script(
			'algolia-js-vars',
			'print_algolia_hits_widget',
			'true'
		);

		$results_html = '';

		foreach ( array_keys( $this->post_types ) as $post_type ) {
			$results_html .= '<div class="algolia-hits-' . esc_attr( $post_type ) . '"></div>';
		}

		return $results_html;
	}
}
