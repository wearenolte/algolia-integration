<?php

namespace AlgoliaIntegration\wp\dashboard;

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
	 * @var      string $plugin_name
	 */
	private $plugin_name;

	/**
	 * The settings page slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_page
	 */
	private $settings_page;

	/**
	 * The settings page URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_page_url
	 */
	private $settings_page_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( string $plugin_name ) {
		$this->plugin_name       = $plugin_name;
		$this->settings_page     = $plugin_name . '-settings';
		$this->settings_page_url = 'options-general.php';

		add_action( 'admin_menu', [ $this, 'add_subpage' ], 9 );
		add_action( 'admin_init', [ $this, 'register_sections_fields' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( ALGOLIA_INTEGRATION_PLUGIN_FILE ), [ $this, 'add_settings_link' ] );
	}

	/**
	 * Adds the settings sub page.
	 *
	 * @since 1.0.0
	 */
	public function add_subpage() {
		add_submenu_page(
			$this->settings_page_url,
			'Algolia Integration Settings',
			'Algolia Integration',
			'administrator',
			$this->settings_page,
			[ $this, 'display_settings' ]
		);
	}

	/**
	 * Initialize the section and fields.
	 *
	 * @since 1.0.0
	 */
	public function register_sections_fields() {
		new CredentialsSection( $this->settings_page );
		new PostsSyncSection( $this->settings_page );
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

	/**
	 * Add the Settings page link in the Plugins List page.
	 *
	 * @since 1.0.0
	 * @param array $links The links list.
	 * @return array
	 */
	public function add_settings_link( array $links ) : array {
		$link = '<a href="'
					. admin_url( $this->settings_page_url . '?page=' . $this->settings_page )
					. '">' . __( 'Settings' ) . '</a>';

		array_unshift( $links, $link );

		return $links;
	}
}
