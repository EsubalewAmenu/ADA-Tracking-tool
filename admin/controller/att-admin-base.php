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
class Att_admin_Base
{


    public function __construct()
    {
    }
    function att_base_menu_section()
    {

        $Att_admin_transactions = new Att_admin_transactions();
        $Att_admin_settings = new Att_admin_settings();
        $Att_admin_cron_schedule = new Att_admin_cron_schedule();

        $capability = "manage_options";

        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'Cron Schedule',                    // Page title
            'Cron Schedule',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-cron-schedule', // Menu slug
            array($Att_admin_cron_schedule, "att_menu_cron_schedule_OnClick") // Callback function
        );

        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'How to use',                    // Page title
            'How to use',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-how-to-use', // Menu slug
            array($this, "att_menu_page_on_click") // Callback function
        );



        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'My Transactions',                    // Page title
            'My Transactions',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-my-transactions', // Menu slug
            array($Att_admin_transactions, "att_menu_my_transactions_OnClick") // Callback function
        );


        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'Setting',                    // Page title
            'Setting',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-settings', // Menu slug
            array($Att_admin_settings, "att_menu_setting_OnClick") // Callback function
        );
    }

    public function att_menu_page_on_click()
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'partials/info/how-to-use.php';
    }
}
