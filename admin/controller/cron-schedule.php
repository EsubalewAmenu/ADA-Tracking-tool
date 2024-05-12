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
class Att_admin_cron_schedule
{

    public function att_menu_cron_schedule_OnClick()
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'partials/settings/cron-schedule.php';
    }

    // Register Settings and Add Fields
    function att_register_settings()
    {
        register_setting('att-cron-options', 'att_cron_schedule');
        add_settings_section(
            'att_cron_main',
            'Config your cron schedule settings below.',
            array($this, 'att_cron_main_text'),
            'att-cron-schedule'
        );

        add_settings_field(
            "att_cron_schedule_fieldtezt",
            'Cron Schedule status',
            function () {

                $hook = 'att_cron_hook';

                $timestamp = wp_next_scheduled($hook);

                if ($timestamp) {
                    $current_time = current_time('timestamp'); // Get the current time with WordPress time zone
                    $time_difference = $timestamp - $current_time;

                    $formatted_time = get_date_from_gmt(date('Y-m-d H:i:s', $timestamp), 'Y-m-d H:i:s');
                    $hours = floor($time_difference / 3600);
                    $minutes = floor(($time_difference % 3600) / 60);
                    $seconds = $time_difference % 60;


                    $name = "att_cron_schedule_fieldtezt";
                    $description =  'Cron Schedule is running. Next run: ' . $formatted_time . "<br>" .
                        'Time until next run: ' . $hours . ' hours, ' . $minutes . ' minutes, and ' . $seconds . ' seconds.';
                    echo $description;
                } else {

                    $name = "att_cron_schedule_fieldtezt";
                    $description = "Cron Schedule is not running.";
                    echo $description;
                }
            },
            'att-cron-schedule',
            'att_cron_main'
        );
        add_settings_field(
            "att_cron_schedule_field",
            'Cron Schedule',
            function () {
                $value = get_option('att_cron_schedule', 'hourly'); // Default to 'hourly' if not set
                echo "<select id='att_cron_schedule_field' name='att_cron_schedule'>";
                echo "<option value='every_minute' " . selected($value, 'every_minute', false) . ">Every Minute</option>";
                echo "<option value='every_five_minutes' " . selected($value, 'every_five_minutes', false) . ">Every 5 Minutes</option>";
                echo "<option value='hourly' " . selected($value, 'hourly', false) . ">Hourly</option>";
                echo "<option value='twicedaily' " . selected($value, 'twicedaily', false) . ">Twice Daily</option>";
                echo "<option value='daily' " . selected($value, 'daily', false) . ">Daily</option>";
                echo "</select>";
            },
            'att-cron-schedule',
            'att_cron_main'
        );
    }

    function att_cron_main_text()
    {
        // echo '<p>Enter your cron schedule settings below.</p>';
    }


    // Managing the Cron Jobs
    function att_update_cron_job()
    {

        $schedule = get_option('att_cron_schedule');
        $hook = 'att_cron_hook';

        $current_time = current_time('timestamp'); // Get the current time according to WordPress
        $next_scheduled = wp_next_scheduled($hook); // Check when the cron is scheduled


        // Get all registered schedules
        $schedules = wp_get_schedules();

        if ($next_scheduled && ($current_time > $next_scheduled)) {
            self::att_execute_cron_job();
        }
        if ((!$next_scheduled || $current_time > $next_scheduled) && isset($schedules[$schedule]) && !empty($next_scheduled)) {
            wp_clear_scheduled_hook($hook);
            $interval = $schedules[$schedule]['interval'];
            wp_schedule_event(time() + $interval, $schedule, $hook);
        }
    }

    function att_execute_cron_job()
    {


        $name = "receiving_address";
        $options = get_option('ada_tracking_option');
        $receiving_address = isset($options[$name]) ? esc_attr($options[$name]) : '';
        $ATTP_mail_templete_post_type_Admin = new ATTP_mail_templete_post_type_Admin();

        // $receiving_address = '';
        if ($receiving_address != '') {
            include_once plugin_dir_path(dirname(__FILE__)) . '../common/fetch-data.php';
            $ATTP_Fetch_Data = new ATTP_Fetch_Data();



			
			$count = isset($options["attp_tx_per_page"]) ? esc_attr($options["attp_tx_per_page"]) : 10;			
            $page = 1;
            $order = "asc";
            $block = isset($options["last_synced_block"]) ? esc_attr($options["last_synced_block"]) : '0';
            $data = $ATTP_Fetch_Data->get_transactions($receiving_address, $count, $page, $order, $block);

            if (is_array($data)) {
                for ($i = 0; $i < sizeof($data); $i++) {
                    $block_height = $data[$i]['block_height'];

                    $options = get_option('ada_tracking_option');
                    $last_synced_block = isset($options["last_synced_block"]) ? esc_attr($options["last_synced_block"]) : '0';
                    if ($block_height > $last_synced_block) {
                        $options['last_synced_block'] = $block_height;
                        update_option('ada_tracking_option', $options);
                    }
                }



                $notif_email_address = isset($options['notif_email_address']) ? esc_attr($options['notif_email_address']) : '';
                $bodyReplacements['site_admin_name'] = "test_site_admin_name";
                $ATTP_mail_templete_post_type_Admin->template($notif_email_address, 'new-transaction-templete', $data, $bodyReplacements);


            }
        }
    }



    // Handle Form Submissions to Start/Stop Cron Jobs

    function att_handle_cron_actions()
    {
        if (isset($_POST['att_start_cron'])) {
            if (check_admin_referer('att_cron_actions', 'att_cron_nonce')) {
                if (current_user_can('manage_options')) {
                    self::att_start_cron_job();
                }
            }
        } elseif (isset($_POST['att_stop_cron'])) {
            if (check_admin_referer('att_cron_actions', 'att_cron_nonce')) {
                if (current_user_can('manage_options')) {
                    self::att_stop_cron_job();
                }
            }
        }
    }


    function att_start_cron_job()
    {
        $schedule = get_option('att_cron_schedule');
        $hook = 'att_cron_hook';

        // Clear any existing hook
        wp_clear_scheduled_hook($hook);
        // Schedule a new event if not already scheduled
        if (!wp_next_scheduled($hook)) {
            wp_schedule_event(time() + 20, $schedule, $hook);
        }
    }

    function att_stop_cron_job()
    {
        $hook = 'att_cron_hook';
        wp_clear_scheduled_hook($hook);
    }


    function add_custom_cron_intervals($schedules)
    {
        // Add a custom interval of every 1 minutes
        $schedules['every_minute'] = array(
            'interval' => 20,  // Time in seconds
            'display'  => __('Every Five Minutes')
        );

        // Add a custom interval of every 5 minutes
        $schedules['every_five_minutes'] = array(
            'interval' => 300,  // Time in seconds
            'display'  => __('Every Five Minutes')
        );

        return $schedules;
    }
}
