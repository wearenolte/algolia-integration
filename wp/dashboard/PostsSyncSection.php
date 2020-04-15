<?php

namespace AlgoliaIntegration\wp\dashboard;

use AlgoliaIntegration\wp\OptionsApiKeys;

/**
 * Creates the Post Sync section in the Settings page.
 *
 * Class PostsSyncSection.
 */
class PostsSyncSection {
	/**
	 * The section id name.
	 *
	 * @since 1.0.0
	 * @var string $section_id
	 */
	private $section_id;

	/**
	 * The settings page name.
	 *
	 * @since 1.0.0
	 * @var string $settings_page
	 */
	private $settings_page;

	/**
	 * Post Sync section constructor.
	 *
	 * @since 1.0.0
	 * @param string $settings_page The settings page name.
	 */
	public function __construct( string $settings_page ) {
		$this->section_id    = 'post_types_sync';
		$this->settings_page = $settings_page;

		self::add_section();
		self::add_fields();
	}

	/**
	 * Add Section.
	 *
	 * @since 1.0.0
	 */
	private function add_section() {
		add_settings_section(
			$this->section_id,
			'Post Types to sync',
			function() {},
			$this->settings_page
		);
	}

	/**
	 * Add fields to the section.
	 *
	 * @since 1.0.0
	 */
	private function add_fields() {
		$post_type_args = array(
			'public' => true,
		);

		$checkboxes = [];

		foreach ( get_post_types( $post_type_args, 'objects' ) as $post_type ) {
			if ( 'Media' === $post_type->label ) {
				continue;
			}

			$checkboxes[] = [
				'id'    => $post_type->name,
				'name'  => $post_type->label,
				'label' => $post_type->label,
			];
		}

		new SettingCheckboxGroup(
			OptionsApiKeys::FIELD_POST_TYPES,
			$checkboxes,
			$this->settings_page,
			$this->section_id
		);
	}
}
