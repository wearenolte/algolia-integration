<?php

namespace AlgoliaIntegration\wp;

/**
 * Class SettingsPage.
 *
 * Creates the Settings sub page in the Settings section.
 *
 * @package AlgoliaIntegration\WP
 */
class SettingsPage {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The settings page slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_page TThe settings page slug.
	 */
	private $settings_page;

	/**
	 * The APP ID options key where the data is saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options key.
	 */
	const FIELD_APP_ID = 'algolia_integration_app_id';

	/**
	 * The Admin API key options key where the data is saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options key.
	 */
	const FIELD_ADMIN_API_KEY = 'algolia_integration_admin_api_key';

	/**
	 * The test APP ID options key where the data is saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options key.
	 */
	const FIELD_APP_ID_TEST = 'algolia_integration_app_id_test';

	/**
	 * The test Admin API key options key where the data is saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options key.
	 */
	const FIELD_ADMIN_API_KEY_TEST = 'algolia_integration_admin_api_key_test';

	/**
	 * The post types options key where the data is saved.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $options The options key.
	 */
	const FIELD_POST_TYPES = 'algolia_integration_post_types';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name   = $plugin_name;
		$this->settings_page = $plugin_name . '-settings';

		add_action( 'admin_menu', [ $this, 'add_subpage' ], 9 );
		add_action( 'admin_init', [ $this, 'register_sections_fields' ] );
	}

	/**
	 * Adds the settings sub page.
	 *
	 * @since 1.0.0
	 */
	public function add_subpage() {
		add_submenu_page(
			'options-general.php',
			'Algolia Integration Settings',
			'Algolia Integration',
			'administrator',
			$this->settings_page,
			[ $this, 'display_settings' ]
		);
	}

	/**
	 * Registers the section and fields.
	 *
	 * @since 1.0.0
	 */
	public function register_sections_fields() {
		$section_1_id = 'credentials';
		$section_2_id = 'test_credentials';
		$section_3_id = 'post_types_sync';

		add_settings_section(
			$section_1_id,
			'Production Credentials',
			function() {},
			$this->settings_page
		);

		add_settings_section(
			$section_2_id,
			'Test Credentials',
			function() {},
			$this->settings_page
		);

		add_settings_section(
			$section_3_id,
			'Post Types to sync',
			function() {},
			$this->settings_page
		);

		new SettingTextField(
			self::FIELD_APP_ID,
			'App ID',
			$this->settings_page,
			$section_1_id
		);

		new SettingTextField(
			self::FIELD_ADMIN_API_KEY,
			'Admin API key',
			$this->settings_page,
			$section_1_id
		);

		new SettingTextField(
			self::FIELD_APP_ID_TEST,
			'App ID',
			$this->settings_page,
			$section_2_id
		);

		new SettingTextField(
			self::FIELD_ADMIN_API_KEY_TEST,
			'Admin API key',
			$this->settings_page,
			$section_2_id
		);

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
			self::FIELD_POST_TYPES,
			$checkboxes,
			$this->settings_page,
			$section_3_id
		);
	}

	/**
	 * Creates fields for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		// Show error/update messages.
		settings_errors( $this->plugin_name . '_messages' );
		?>
		<div class="wrap">

			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form action="options.php" method="post">
				<?php
				// Output security fields for the registered setting.
				settings_fields( $this->settings_page );

				// Output setting sections and their fields.
				do_settings_sections( $this->settings_page );

				// Output save settings button.
				submit_button( 'Save Settings' );
				?>
			</form>

		</div>
		<?php
	}
}
