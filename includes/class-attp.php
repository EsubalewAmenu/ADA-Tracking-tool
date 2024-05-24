<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Attp
 * @subpackage Attp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Attp
 * @subpackage Attp/includes
 * @author     Esubalew A <esubalew.a2009@gmail.com>
 */
class Attp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Attp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ATTP_VERSION' ) ) {
			$this->version = ATTP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'attp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Attp_Loader. Orchestrates the hooks of the plugin.
	 * - Attp_i18n. Defines internationalization functionality.
	 * - Attp_Admin. Defines all hooks for the admin area.
	 * - Attp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-attp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-attp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-attp-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/attp-admin-base.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/transactions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/cron-schedule.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/mail_template_post_type.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-attp-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/controller/user-transactions.php';

		$this->loader = new Attp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Attp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Attp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Attp_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$attp_admin_base = new Attp_admin_base();
		$this->loader->add_action('admin_menu', $attp_admin_base, 'attp_base_menu_section');

		$Attp_admin_settings = new Attp_admin_settings();
		// Register settings and sections
		$this->loader->add_action('admin_init', $Attp_admin_settings, 'ada_tracking_settings_init');


		$ATTP_mail_template_post_type_Admin = new ATTP_mail_template_post_type_Admin();
		$this->loader->add_action('init', $ATTP_mail_template_post_type_Admin, 'attp_mail_template_format_init', 1, 1);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Attp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		$Attp_public_transactions = new Attp_public_transactions();
		$this->loader->add_shortcode( 'attp_transaction_history_code', $Attp_public_transactions, 'attp_transaction_history_OnClick' );
	

		$this->loader->add_action('wp_ajax_load_transaction_history', $Attp_public_transactions, 'wp_ajax_load_transaction_history');
		$this->loader->add_action('wp_ajax_nopriv_load_transaction_history', $Attp_public_transactions, 'wp_ajax_load_transaction_history');
		
		$Attp_admin_cron_schedule = new Attp_admin_cron_schedule();

		// Register Settings and Add Fields
		$this->loader->add_action('admin_init', $Attp_admin_cron_schedule, 'attp_register_settings');

		// Managing the Cron Jobs
		$this->loader->add_action('init', $Attp_admin_cron_schedule, 'attp_update_cron_job');

		// Handle Form Submissions to Start/Stop Cron Jobs
		$this->loader->add_action('admin_init', $Attp_admin_cron_schedule, 'attp_handle_cron_actions');

		add_filter('cron_schedules', array($Attp_admin_cron_schedule, 'add_custom_cron_intervals'));



		$Attp_admin_transactions = new Attp_admin_transactions();
		$this->loader->add_action('wp_ajax_load_more_transactions', $Attp_admin_transactions, 'load_more_transactions');

		


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Attp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
