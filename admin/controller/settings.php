<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Att_admin
 * @subpackage Att_admin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Att_admin
 * @subpackage Att_admin/admin
 * @author     Esubalew Amenu <esubalew.a2009@gmail.com>
 */
class Att_admin_settings
{

	public function att_menu_setting_OnClick()
	{
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/settings/index.php';
	}


function ada_tracking_settings_init() {
    // Register a setting and its sanitization callback
    register_setting('ada_tracking_settings_group', 'ada_tracking_option', 'sanitize_callback');

    // Add a section and fields to the settings page
    add_settings_section(
        'ada_tracking_settings_section',
        '',//'Custom Plugin Settings Section',
        array($this, 'ada_tracking_settings_section_callback'),
        'ada-tracking-plugin'
    );

    add_settings_field(
        "receiving_address",
        'ADA Receiving Address',
        function () {
            $name = "receiving_address";
            $description = 'Site owner/transaction receiving address';
            self::input_field_callback($name, $description, "text");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );

    add_settings_field(
        "notif_email_address_cb",
        'Notification Email?',
        function () {
            $name = "notif_email_address_cb";
            $description = 'Do you want notification email when there is new incoming transaction?';
            self::input_field_callback($name, $description, "checkbox");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    add_settings_field(
        "notif_email_address",
        'Notification Email address',
        function () {
            $name = "notif_email_address";
            $description = 'Notification email will be sent to the above email when there is new incoming transaction';
            self::input_field_callback($name, $description, "text");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );

    ////////////////////////////////////////////////////////////////////////////////////
    add_settings_field(
        "prefix_filter_cb",
        'Filter tx by prefix?',
        function () {
            $name = "prefix_filter_cb";
            $description = 'Do you want to filter incoming transactions based on note prefix text?';
            self::input_field_callback($name, $description, "checkbox");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    add_settings_field(
        "prefix_filter",
        'Prefix string',
        function () {
            $name = "prefix_filter";
            $description = 'Incoming transactions will be filtered when tx note prefix is matched.';
            self::input_field_callback($name, $description, "text");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    ////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////
    add_settings_field(
        "suffix_filter_cb",
        'Filter tx by suffix?',
        function () {
            $name = "suffix_filter_cb";
            $description = 'Do you want to filter incoming transactions based on note suffix text?';
            self::input_field_callback($name, $description, "checkbox");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    add_settings_field(
        "suffix_filter",
        'Suffix String',
        function () {
            $name = "suffix_filter";
            $description = 'Incoming transactions will be filtered when tx note suffix is matched.';
            self::input_field_callback($name, $description, "text");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    ////////////////////////////////////////////////////////////////////////////////////

    add_settings_field(
        "last_synced",
        'Last Transaction Synced',
        function () {
            $name = "last_synced";
            $description = 'Incoming transactions will synced starting from the above metioned date.';
            self::input_field_callback($name, $description, "datetime-local");
        },
        'ada-tracking-plugin',
        'ada_tracking_settings_section'
    );
    ////////////////////////////////////////////////////////////////////////////////////
}
// Field callback function
function input_field_callback($name, $description, $input_type) {
    $options = get_option('ada_tracking_option');
    $value = isset($options[$name]) ? esc_attr($options[$name]) : '';

    if ($input_type === 'checkbox') {
        $checked = isset($options[$name]) && $options[$name] === 'on' ? 'checked="checked"' : '';
        ?>
        <input type="<?php echo esc_attr($input_type) ?>" name="ada_tracking_option[<?php echo esc_attr($name)  ?>]" <?php echo esc_attr( $checked) ?>/>
        <?php
    } else { ?>
        <input type="<?php echo esc_attr($input_type)?>" name="ada_tracking_option[<?php echo esc_attr($name)?>]" value="<?php echo esc_attr($value)?>" />
    <?php }
    ?>
    <p class="description"><?php echo esc_attr($description)?></p>
<?php
}



// Section callback function
function ada_tracking_settings_section_callback() {
    // echo '<p>Enter your settings below:</p>';
}


// Sanitization callback function
function sanitize_callback($input) {
    // Sanitize input here
    return $input;
}
}
