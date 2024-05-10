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
class Att_admin_transactions
{



	public function att_menu_my_transactions_OnClick()
	{

		$name = "receiving_address";
		$options = get_option('ada_tracking_option');
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
			$receiving_addresses_table = new Custom_Table_List($data, $columns, 15);
			$receiving_addresses_table->prepare_items();


			if (isset($_GET['count']) && !empty(esc_attr($_GET['count']))) {
				$attp_tx_per_page = esc_attr($_GET['count']);
			}else{
				$options = get_option('attp_tx_per_page');
				$attp_tx_per_page = isset($options) ? esc_attr($options) : 5;
			}

			include_once plugin_dir_path(dirname(__FILE__)) . 'partials/account/transactions.php';
		}
	}




	public function load_more_transactions()
	{
		check_ajax_referer('load_more_transactions', 'security');

		$options = get_option('ada_tracking_option');
		$receiving_address = isset($options['receiving_address']) ? esc_attr($options['receiving_address']) : '';

		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$count = isset($_POST['count']) ? intval($_POST['count']) : 1;
		$order = 'desc';

		require_once plugin_dir_path(dirname(__FILE__)) . '/../common/fetch-data.php';
		$fetch_data = new ATTP_Fetch_Data();
		$data = $fetch_data->get_transactions($receiving_address, $count, $page, $order);

		if (!empty($data)) {
			require_once plugin_dir_path(dirname(__FILE__)) . '/../common/Custom_Table_List.php';
			$columns = array(
				'is_incoming' => 'Is incoming',
				'amount' => 'Amount',
				'tx_hash' => 'Transaction Hash',
				'time' => 'TX time',
				'message' => 'Message',
				'confirmation' => 'TX confirmation',
			);
			$table = new Custom_Table_List($data, $columns, 15);
			$table->prepare_items();

			ob_start();
			$table->display_rows();
			$rows = ob_get_clean();
			wp_send_json_success(array('rows' => $rows));
		} else {
			wp_send_json_error(array('message' => 'No more transactions'));
		}
	}
}
