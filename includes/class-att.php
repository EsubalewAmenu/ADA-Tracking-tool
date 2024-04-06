<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https:///github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Att
 * @subpackage Att/includes
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
 * @package    Att
 * @subpackage Att/includes
 * @author     Esubalew A <esubalew.a2009@gmail.com>
 */
class Att {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Att_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'ATT_VERSION' ) ) {
			$this->version = ATT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'att';

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
	 * - Att_Loader. Orchestrates the hooks of the plugin.
	 * - Att_i18n. Defines internationalization functionality.
	 * - Att_Admin. Defines all hooks for the admin area.
	 * - Att_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-att-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-att-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-att-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/att-admin-base.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controller/transactions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-att-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/controller/user-transactions.php';

		$this->loader = new Att_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Att_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Att_i18n();

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

		$plugin_admin = new Att_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$att_admin_base = new Att_admin_base();
		$this->loader->add_action('admin_menu', $att_admin_base, 'att_base_menu_section');

		$Att_admin_settings = new Att_admin_settings();
		// Register settings and sections
		$this->loader->add_action('admin_init', $Att_admin_settings, 'ada_tracking_settings_init');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Att_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		$Att_public_transactions = new Att_public_transactions();
		$this->loader->add_shortcode( 'att_transaction_history_code', $Att_public_transactions, 'att_transaction_history_OnClick' );
	

		$this->loader->add_action('wp_ajax_load_transaction_history', $Att_public_transactions, 'wp_ajax_load_transaction_history');
		$this->loader->add_action('wp_ajax_nopriv_load_transaction_history', $Att_public_transactions, 'wp_ajax_load_transaction_history');
	
	
		add_action('wp_ajax_load_transaction_history', 'load_transaction_history_callback');

		function load_transaction_history_callback() {
			// Verify nonce
			if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'load_transaction_history_nonce' ) ) {
				wp_send_json_error( 'Nonce verification failed.', 403 );
			}
		
			// Your AJAX handling code
		}
		
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
	 * @return    Att_Loader    Orchestrates the hooks of the plugin.
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
