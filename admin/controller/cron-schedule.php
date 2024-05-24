<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Attp_admin
 * @subpackage Attp_admin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Attp_admin
 * @subpackage Attp_admin/admin
 * @author     Esubalew Amenu <esubalew.a2009@gmail.com>
 */
class Attp_admin_cron_schedule
{

    public function attp_menu_cron_schedule_OnClick()
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'partials/settings/cron-schedule.php';
    }

    // Register Settings and Add Fields
    function attp_register_settings()
    {
        register_setting('attp-cron-options', 'attp_cron_schedule');
        add_settings_section(
            'attp_cron_main',
            'Config your cron schedule settings below.',
            array($this, 'attp_cron_main_text'),
            'attp-cron-schedule'
        );

        add_settings_field(
            "attp_cron_schedule_fieldtezt",
            'Cron Schedule status',
            function () {

                $hook = 'attp_cron_hook';

                $timestamp = wp_next_scheduled($hook);

                if ($timestamp) {
                    $current_time = current_time('timestamp'); // Get the current time with WordPress time zone
                    $time_difference = $timestamp - $current_time;

                    $formatted_time = get_date_from_gmt(gmdate('Y-m-d H:i:s', $timestamp), 'Y-m-d H:i:s');
                    $hours = floor($time_difference / 3600);
                    $minutes = floor(($time_difference % 3600) / 60);
                    $seconds = $time_difference % 60;


                    $description =  'Cron Schedule is running. Next run: ' . $formatted_time . "<br>" .
                        'Time until next run: ' . $hours . ' hours, ' . $minutes . ' minutes, and ' . $seconds . ' seconds.';
                } else {

                    $name = "attp_cron_schedule_fieldtezt";
                    $description = "Cron Schedule is not running.";
                }
                echo wp_kses_post($description);
            },
            'attp-cron-schedule',
            'attp_cron_main'
        );
        add_settings_field(
            "attp_cron_schedule_field",
            'Cron Schedule',
            function () {
                $value = get_option('attp_cron_schedule', 'hourly'); // Default to 'hourly' if not set
                echo "<select id='attp_cron_schedule_field' name='attp_cron_schedule'>";
                echo "<option value='every_minute' " . selected($value, 'every_minute', false) . ">Every Minute</option>";
                echo "<option value='every_five_minutes' " . selected($value, 'every_five_minutes', false) . ">Every 5 Minutes</option>";
                echo "<option value='hourly' " . selected($value, 'hourly', false) . ">Hourly</option>";
                echo "<option value='twicedaily' " . selected($value, 'twicedaily', false) . ">Twice Daily</option>";
                echo "<option value='daily' " . selected($value, 'daily', false) . ">Daily</option>";
                echo "</select>";
            },
            'attp-cron-schedule',
            'attp_cron_main'
        );
    }

    function attp_cron_main_text()
    {
        // echo '<p>Enter your cron schedule settings below.</p>';
    }


    // Managing the Cron Jobs
    function attp_update_cron_job()
    {

        $schedule = get_option('attp_cron_schedule');
        $hook = 'attp_cron_hook';

        $current_time = current_time('timestamp'); // Get the current time according to WordPress
        $next_scheduled = wp_next_scheduled($hook); // Check when the cron is scheduled


        // Get all registered schedules
        $schedules = wp_get_schedules();

        if ($next_scheduled && ($current_time > $next_scheduled)) {
            self::attp_execute_cron_job();
        }
        if ((!$next_scheduled || $current_time > $next_scheduled) && isset($schedules[$schedule]) && !empty($next_scheduled)) {
            wp_clear_scheduled_hook($hook);
            $interval = $schedules[$schedule]['interval'];
            wp_schedule_event(time() + $interval, $schedule, $hook);
        }
    }

    function attp_execute_cron_job()
    {


        $name = "attp_receiving_address";
        $options = get_option('attp_option');
        $receiving_address = isset($options[$name]) ? esc_attr($options[$name]) : '';
        $ATTP_mail_template_post_type_Admin = new ATTP_mail_template_post_type_Admin();

        $notif_email_address_cb = isset($options["attp_notif_email_address_cb"]) && $options["attp_notif_email_address_cb"] === 'on' ? true : false;
        // $receiving_address = '';
        if (substr($receiving_address, 0, 4) === "addr" && $notif_email_address_cb) {
            include_once plugin_dir_path(dirname(__FILE__)) . '../common/fetch-data.php';
            $Attp_Fetch_Data = new Attp_Fetch_Data();

            $count = isset($options["attp_attp_tx_per_page"]) ? esc_attr($options["attp_attp_tx_per_page"]) : 10;
            $page = 1;
            $order = "asc";
            $block = isset($options["attp_last_synced_block"]) ? esc_attr($options["attp_last_synced_block"]) : '0';
            $data = $Attp_Fetch_Data->get_transactions($receiving_address, $count, $page, $order, $block);
            if (is_array($data)) {


                $prefix_filter_cb = isset($options["attp_prefix_filter_cb"]) && $options["attp_prefix_filter_cb"] === 'on' ? true : false;
                $prefix_filter = isset($options["attp_prefix_filter"]) ? esc_attr($options["attp_prefix_filter"]) : '';

                $suffix_filter_cb = isset($options["attp_suffix_filter_cb"]) && $options["attp_suffix_filter_cb"] === 'on' ? true : false;
                $suffix_filter = isset($options["attp_suffix_filter"]) ? esc_attr($options["attp_suffix_filter"]) : '';


                $removable_tx_indexs = array();
                for ($single_tx_index = 0; $single_tx_index < sizeof($data); $single_tx_index++) {


                    $block_height = $data[$single_tx_index]['block_height'];
                    $tx_index = $data[$single_tx_index]['tx_index'];

                    $options = get_option('attp_option');
                    $last_synced_block = isset($options["attp_last_synced_block"]) ? esc_attr($options["attp_last_synced_block"]) : '0';
                    $last_synced_tx_index = isset($options["attp_last_synced_tx_index"]) ? esc_attr($options["attp_last_synced_tx_index"]) : '0';

                    if ($block_height > $last_synced_block || ($block_height == $last_synced_block && $tx_index > $last_synced_tx_index)) {
                        $options['attp_last_synced_block'] = $block_height;
                        $options['attp_last_synced_tx_index'] = $tx_index;
                        update_option('attp_option', $options);


                        $message = $data[$single_tx_index]['message'] != null ? $data[$single_tx_index]['message'] : '';

                        if ($prefix_filter_cb && substr($message, 0, strlen($prefix_filter)) !== $prefix_filter) {
                            $removable_tx_indexs[] = $single_tx_index;
                        } else if ($suffix_filter_cb && substr($message, -strlen($suffix_filter)) !== $suffix_filter) {
                            $removable_tx_indexs[] = $single_tx_index;
                        }
                    } else {
                        $removable_tx_indexs[] = $single_tx_index;
                    }
                }
                foreach ($removable_tx_indexs as $single_removable_index) {
                    unset($data[$single_removable_index]);
                }

                if ($data) {
                    $notif_email_address = isset($options['attp_notif_email_address']) ? esc_attr($options['attp_notif_email_address']) : '';
                    $bodyReplacements['site_admin_name'] = get_option('blogname');
                    $ATTP_mail_template_post_type_Admin->template($notif_email_address, 'new-transaction-template', $data, $bodyReplacements);
                }
            }
        }
    }



    // Handle Form Submissions to Start/Stop Cron Jobs

    function attp_handle_cron_actions()
    {
        if (isset($_POST['attp_start_cron'])) {
            if (check_admin_referer('attp_cron_actions', 'attp_cron_nonce')) {
                if (current_user_can('manage_options')) {
                    self::attp_start_cron_job();
                }
            }
        } elseif (isset($_POST['attp_stop_cron'])) {
            if (check_admin_referer('attp_cron_actions', 'attp_cron_nonce')) {
                if (current_user_can('manage_options')) {
                    self::attp_stop_cron_job();
                }
            }
        }
    }


    function attp_start_cron_job()
    {
        $schedule = get_option('attp_cron_schedule');
        $hook = 'attp_cron_hook';

        // Clear any existing hook
        wp_clear_scheduled_hook($hook);
        // Schedule a new event if not already scheduled
        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time() + 20, $schedule, $hook);
        }
    }

    function attp_stop_cron_job()
    {
        $hook = 'attp_cron_hook';
        wp_clear_scheduled_hook($hook);
    }


    function add_custom_cron_intervals($schedules)
    {
        // Add a custom interval of every 1 minutes
        $schedules['every_minute'] = array(
            'interval' => 20,  // Time in seconds
            'display'  => __('Every One Minutes', 'ada-tracking-tool')
        );

        // Add a custom interval of every 5 minutes
        $schedules['every_five_minutes'] = array(
            'interval' => 300,  // Time in seconds
            'display'  => __('Every Five Minutes', 'ada-tracking-tool')
        );

        return $schedules;
    }
}
