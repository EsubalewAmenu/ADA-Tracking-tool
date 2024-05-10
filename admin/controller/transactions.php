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
			require_once plugin_dir_path(dirname(__FILE__)) . '/../common/fetch-data.php';
			$fetch_data = new ATTP_Fetch_Data();

			$count = 5;
			$page = 1;
			$order = 'desc';

			$data = $fetch_data->get_transactions($receiving_address, $count, $page, $order);

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

			include_once plugin_dir_path(dirname(__FILE__)) . 'partials/account/transactions.php';
		}
	}
}
