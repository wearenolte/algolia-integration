<?php

namespace AlgoliaIntegration\wp\dashboard;

use AlgoliaIntegration\wp\OptionsApiKeys;

/**
 * Creates the Credentials section in the Settings page.
 *
 * Class CredentialsSection
 */
class CredentialsSection {
	/**
	 * The section 1 id name.
	 *
	 * @since 1.0.0
	 * @var string $section_1_id
	 */
	private $section_1_id;

	/**
	 * The section 2 id name.
	 *
	 * @since 1.0.0
	 * @var string $section_1_id
	 */
	private $section_2_id;

	/**
	 * The settings page name.
	 *
	 * @since 1.0.0
	 * @var string $settings_page
	 */
	private $settings_page;

	/**
	 * Credentials section constructor.
	 *
	 * @since 1.0.0
	 * @param string $settings_page The settings page name.
	 */
	public function __construct( string $settings_page ) {
		$this->section_1_id  = 'credentials';
		$this->section_2_id  = 'test_credentials';
		$this->settings_page = $settings_page;

		self::add_section();
		self::add_fields();
	}

	/**
	 * Add the sections.
	 *
	 * @since 1.0.0
	 */
	private function add_section() {
		add_settings_section(
			$this->section_1_id,
			'Production Credentials',
			function() {},
			$this->settings_page
		);

		add_settings_section(
			$this->section_2_id,
			'Test Credentials',
			function() {},
			$this->settings_page
		);
	}

	/**
	 * Add the fields in the sections.
	 *
	 * @since 1.0.0
	 */
	private function add_fields() {
		new SettingTextField(
			OptionsApiKeys::FIELD_APP_ID,
			'App ID',
			$this->settings_page,
			$this->section_1_id
		);

		new SettingTextField(
			OptionsApiKeys::FIELD_ADMIN_API_KEY,
			'Admin API key',
			$this->settings_page,
			$this->section_1_id
		);

		new SettingTextField(
			OptionsApiKeys::FIELD_SEARCH_ONLY_KEY,
			'Search-Only API key',
			$this->settings_page,
			$this->section_1_id
		);

		new SettingTextField(
			OptionsApiKeys::FIELD_APP_ID_TEST,
			'App ID',
			$this->settings_page,
			$this->section_2_id
		);

		new SettingTextField(
			OptionsApiKeys::FIELD_ADMIN_API_KEY_TEST,
			'Admin API key',
			$this->settings_page,
			$this->section_2_id
		);

		new SettingTextField(
			OptionsApiKeys::FIELD_SEARCH_ONLY_KEY_TEST,
			'Search-Only API key',
			$this->settings_page,
			$this->section_2_id
		);
	}
}
