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
        include_once plugin_dir_path(dirname(__FILE__)) . 'partials/settings/index.php';
    }


    function ada_tracking_settings_init()
    {
        // Register a setting and its sanitization callback
        register_setting('ada_tracking_settings_group', 'ada_tracking_option', 'sanitize_callback');

        // Add a section and fields to the settings page
        add_settings_section(
            'ada_tracking_settings_section',
            '', //'Custom Plugin Settings Section',
            array($this, 'ada_tracking_settings_section_callback'),
            'ada-tracking-plugin'
        );

        add_settings_field(
            "blockfrost_api",
            'Blockfrost project ID',
            function () {
                $name = "blockfrost_api";
                $description = 'Blockfrost mainnet project id';

                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>
            <input type="text" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
            },
            'ada-tracking-plugin',
            'ada_tracking_settings_section'
        );

        add_settings_field(
            "receiving_address",
            'ADA Receiving Address',
            function () {
                $name = "receiving_address";
                $description = 'Site owner/transaction receiving address';

                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>
            <input type="text" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
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

                $options = get_option('ada_tracking_option');
                $checked = isset($options[$name]) && $options[$name] === 'on' ? 'checked="checked"' : '';
        ?>
            <input type="checkbox" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" <?php echo esc_attr($checked) ?> />
            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
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


                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>
            <input type="text" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
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

                $options = get_option('ada_tracking_option');
                $checked = isset($options[$name]) && $options[$name] === 'on' ? 'checked="checked"' : '';
        ?>
            <input type="checkbox" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" <?php echo esc_attr($checked) ?> />
            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
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

                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>
            <input type="text" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
            },
            'ada-tracking-plugin',
            'ada_tracking_settings_section'
        );
        ////////////////////////////////////////////////////////////////////////////////////
        add_settings_field(
            "suffix_filter_cb",
            'Filter tx by suffix?',
            function () {
                $name = "suffix_filter_cb";
                $description = 'Do you want to filter incoming transactions based on note suffix text?';

                $options = get_option('ada_tracking_option');
                $checked = isset($options[$name]) && $options[$name] === 'on' ? 'checked="checked"' : '';
        ?>
            <input type="checkbox" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" <?php echo esc_attr($checked) ?> />
            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
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


                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>
            <input type="text" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
            },
            'ada-tracking-plugin',
            'ada_tracking_settings_section'
        );

        add_settings_field(
            "attp_tx_per_page",
            'TX per page',
            function () {
                $name = "attp_tx_per_page";
                $description = 'Default number of transactions that will be loaded per page';


                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : ''; ?>


            <select name="ada_tracking_option[<?php echo esc_attr($name) ?>]">
                <option value="1" <?php if ($value == 1) echo esc_attr('selected'); ?>>1</option>
                <option value="5" <?php if ($value == 5) echo esc_attr('selected'); ?>>5</option>
                <option value="10" <?php if ($value == 10) echo esc_attr('selected'); ?>>10</option>
                <option value="25" <?php if ($value == 25) echo esc_attr('selected'); ?>>25</option>
                <option value="50" <?php if ($value == 50) echo esc_attr('selected'); ?>>50</option>
                <option value="100" <?php if ($value == 100) echo esc_attr('selected'); ?>>100</option>
            </select>

            <p class="description"><?php echo esc_attr($description) ?></p>
        <?php
            },
            'ada-tracking-plugin',
            'ada_tracking_settings_section'
        );
        ////////////////////////////////////////////////////////////////////////////////////

        add_settings_field(
            "last_synced",
            'Last Synced TX block',
            function () {
                $name = "last_synced_block";
                $description = 'Incoming transactions will be synced starting from the above metioned block.';


                $options = get_option('ada_tracking_option');
                $value = isset($options[$name]) ? esc_attr($options[$name]) : '0'; ?>
            <input type="number" name="ada_tracking_option[<?php echo esc_attr($name) ?>]" value="<?php echo esc_attr($value) ?>" />

            <p class="description"><?php echo esc_attr($description) ?></p>
<?php
            },
            'ada-tracking-plugin',
            'ada_tracking_settings_section'
        );
        ////////////////////////////////////////////////////////////////////////////////////
    }



    // Section callback function
    function ada_tracking_settings_section_callback()
    {
        // echo '<p>Enter your settings below:</p>';
    }


    /**
     * Sanitization and validation callback function
     *
     * @param array $input Raw input data from the form
     * @return array Sanitized and validated data
     */
    function sanitize_callback($input)
    {
        $sanitized_input = array();

        // Validate and sanitize the ADA Receiving Address
        if (isset($input['receiving_address'])) {
            // Basic validation: check if the address is not empty
            if (!empty($input['receiving_address'])) {
                // Sanitize the address (as a simple text input)
                $sanitized_input['receiving_address'] = sanitize_text_field($input['receiving_address']);
            } else {
                // Set an error message if the address is empty
                add_settings_error(
                    'ada_tracking_option',
                    'ada_tracking_receiving_address_error',
                    'The ADA Receiving Address cannot be empty.',
                    'error'
                );
            }
        }

        // Validate and sanitize the Notification Email Checkbox
        if (isset($input['notif_email_address_cb'])) {
            // Checkbox is either on or off
            $sanitized_input['notif_email_address_cb'] = $input['notif_email_address_cb'] === 'on' ? 'on' : '';
        }

        // Validate and sanitize the Notification Email Address
        if (isset($input['notif_email_address'])) {
            // Basic validation: check if the email is valid
            if (is_email($input['notif_email_address'])) {
                $sanitized_input['notif_email_address'] = sanitize_email($input['notif_email_address']);
            } else {
                add_settings_error(
                    'ada_tracking_option',
                    'ada_tracking_notif_email_address_error',
                    'Invalid email address provided.',
                    'error'
                );
            }
        }

        // Validate and sanitize other fields as needed...
        // For each additional field, repeat similar checks and sanitizations

        return $sanitized_input;
    }
}
