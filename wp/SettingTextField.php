<?php

namespace AlgoliaIntegration\wp;

/**
 * Class SettingTextField.
 *
 * Creates a text input field and handles saving and loading using the Settings API.
 *
 * @package AlgoliaIntegration\wp
 */
class SettingTextField {
	/**
	 * The key of the option where the field saves the value.
	 *
	 * @since 1.0.0
	 * @var string The option key.
	 */
	private $option_key;

	/**
	 * SettingTextField constructor.
	 *
	 * @since 1.0.0
	 * @param string $field_id The text input id.
	 * @param string $field_label The text inout label.
	 * @param string $settings_page The settings page name where this fields are added.
	 * @param string $section_name The section name where this fields are added.
	 */
	public function __construct(
		string $field_id,
		string $field_label,
		string $settings_page,
		string $section_name
	) {
		$this->option_key = $field_id;

		register_setting(
			$settings_page,
			$this->option_key,
			[ $this, 'validation_callback' ]
		);

		add_settings_field(
			$field_id,
			$field_label,
			[ $this, 'add_field' ],
			$settings_page,
			$section_name,
			[
				'id' => $field_id,
			]
		);
	}

	/**
	 * Renders a text input field.
	 *
	 * @since 1.0.0
	 * @param array $args The checkbox data.
	 */
	public function add_field( array $args ) {
		$field_id = $args['id'];

		// Get the value of the setting we've registered with register_setting().
		$value = get_option( $this->option_key );

		// Output the field.
		?>

		<input
			type="text"
			class="regular-text"
			name="<?php echo esc_attr( $this->option_key ); ?>"
			id="<?php echo esc_attr( $field_id ); ?>"
			value="<?php echo esc_attr( $value ); ?>">

		<?php
	}

	/**
	 * Sanitizes the text fields.
	 *
	 * @since 1.0.0
	 * @param string $input The input text.
	 * @return string
	 */
	public function validation_callback( $input ) {
		return sanitize_text_field( $input );
	}
}
