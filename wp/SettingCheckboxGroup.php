<?php

namespace AlgoliaIntegration\wp;

/**
 * Class SettingCheckboxGroup.
 *
 * Creates multiple checkboxes of the same group and handles saving and loading using the Settings API.
 *
 * @package AlgoliaIntegration\wp
 */
class SettingCheckboxGroup {
	/**
	 * The key of the option where the field saves the value.
	 *
	 * @since 1.0.0
	 * @var string The option key.
	 */
	private $option_key;

	/**
	 * SettingCheckboxGroup constructor.
	 *
	 * @since 1.0.0
	 * @param string $field_group The checkboxes group.
	 * @param array  $checkboxes_list The list of checkboxes.
	 * @param string $settings_page The settings page name where this fields are added.
	 * @param string $section_name The section name where this fields are added.
	 */
	public function __construct(
		string $field_group,
		array $checkboxes_list,
		string $settings_page,
		string $section_name
	) {
		$this->option_key = $field_group;

		register_setting(
			$settings_page,
			$this->option_key
		);

		foreach ( $checkboxes_list as $checkbox ) {
			add_settings_field(
				$checkbox['id'],
				'',
				[ $this, 'add_field' ],
				$settings_page,
				$section_name,
				[
					'id'    => $checkbox['id'],
					'group' => $this->option_key,
					'label' => $checkbox['label'],
				]
			);
		}
	}

	/**
	 * Renders a checkbox field.
	 *
	 * @since 1.0.0
	 * @param array $args The checkbox data.
	 */
	public function add_field( array $args ) {
		$field_id    = $args['id'];
		$field_group = $args['group'];

		// Get the value of the setting we've registered with register_setting().
		$value = get_option( $field_group );

		// Output the field.
		?>

		<input
			type="checkbox"
			class=""
			name="<?php echo esc_attr( $field_group ); ?>[<?php echo esc_attr( $field_id ); ?>]"
			id="<?php echo esc_attr( $field_id ); ?>"
			value="1"
			<?php checked( 1, $value[ $field_id ], true ); ?>>

		<label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $args['label'] ); ?></label>

		<?php
	}
}
