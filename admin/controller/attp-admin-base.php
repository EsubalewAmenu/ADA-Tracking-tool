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
class Attp_admin_Base
{


    public function __construct()
    {
    }
    function attp_base_menu_section()
    {

        $Attp_admin_transactions = new Attp_admin_transactions();
        $Attp_admin_settings = new Attp_admin_settings();
        $Attp_admin_cron_schedule = new Attp_admin_cron_schedule();

        $capability = "manage_options";

        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'How to use',                    // Page title
            'How to use',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-how-to-use', // Menu slug
            array($this, "attp_menu_page_on_click"), // Callback function
            0                                            // Position
        );
        
        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'Cron Schedule',                    // Page title
            'Cron Schedule',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-cron-schedule', // Menu slug
            array($Attp_admin_cron_schedule, "attp_menu_cron_schedule_OnClick") // Callback function
        );

        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'My Transactions',                    // Page title
            'My Transactions',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-my-transactions', // Menu slug
            array($Attp_admin_transactions, "attp_menu_my_transactions_OnClick") // Callback function
        );


        // Adding submenu page to the 'attp_mails' post type
        add_submenu_page(
            'edit.php?post_type=attp_mails',      // Parent slug
            'Setting',                    // Page title
            'Setting',                    // Menu title
            $capability,                          // Capability
            'edit.php?post_type=attp_mails-settings', // Menu slug
            array($Attp_admin_settings, "attp_menu_setting_OnClick") // Callback function
        );
    }

    public function attp_menu_page_on_click()
    {
        include_once plugin_dir_path(dirname(__FILE__)) . 'partials/info/how-to-use.php';
    }
}
