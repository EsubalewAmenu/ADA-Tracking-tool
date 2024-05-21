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
class Attp_admin_transactions
{



	public function attp_menu_my_transactions_OnClick()
	{

		$name = "attp_receiving_address";
		$options = get_option('attp_option');
		$receiving_address = isset($options[$name]) ? esc_attr($options[$name]) : '';


		if ($receiving_address == '') {
		} else {

			$data = [];
			$columns = array(
				'is_incoming' => 'Is incoming',
				'amount' => 'Amount',
				'tx_hash' => 'Transaction Hash',
				'time' => 'TX time',
				'message' => 'Message',
				'confirmation' => 'TX confirmation',
			);


			require_once plugin_dir_path(dirname(__FILE__)) . '/../common/Custom_Table_List.php';
			$receiving_addresses_table = new Attp_Custom_Table_List($data, $columns, 15);
			$receiving_addresses_table->prepare_items();


			$name = "attp_tx_per_page";
			$options = get_option('attp_option');
			$attp_tx_per_page = isset($options[$name]) ? esc_attr($options[$name]) : 5;

			if (isset($_GET['count'], $_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'count_nonce')) {
				if (!empty($_GET['count'])) {
					$attp_tx_per_page = esc_attr(sanitize_text_field($_GET['count']));
				}
			}
			include_once plugin_dir_path(dirname(__FILE__)) . 'partials/account/transactions.php';
			wp_enqueue_script('attp-admin-transactions', plugin_dir_url(__FILE__) . '../js/attp-transactions.js', array('jquery'), '1.0.0', false);
			wp_localize_script('attp-admin-transactions', 'attp_ajax_object', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'count_nonce' => wp_create_nonce('count_nonce'),
				'nonce' => wp_create_nonce('load_more_transactions'),
			));
		}
	}




	public function load_more_transactions()
	{
		check_ajax_referer('load_more_transactions', 'security');

		$options = get_option('attp_option');
		$receiving_address = isset($options['attp_receiving_address']) ? esc_attr($options['attp_receiving_address']) : '';

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$count = isset($_POST['count']) ? intval($_POST['count']) : 1;
		$order = 'desc';

		require_once plugin_dir_path(dirname(__FILE__)) . '/../common/fetch-data.php';
		$Attp_Fetch_Data = new Attp_Fetch_Data();
		$data = $Attp_Fetch_Data->get_transactions($receiving_address, $count, $page, $order);

		if (is_array($data)) {
			require_once plugin_dir_path(dirname(__FILE__)) . '/../common/Custom_Table_List.php';
			$columns = array(
				'is_incoming' => 'Is incoming',
				'amount' => 'Amount',
				'tx_hash' => 'Transaction Hash',
				'time' => 'TX time',
				'message' => 'Message',
				'confirmation' => 'TX confirmation',
			);
			$table = new Attp_Custom_Table_List($data, $columns, 15);
			$table->prepare_items();

			ob_start();
			$table->display_rows();
			$rows = ob_get_clean();
			wp_send_json_success(array('rows' => $rows));
		} else {
			wp_send_json_error(array('message' => $data));
		}
	}
}
