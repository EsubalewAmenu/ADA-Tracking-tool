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


		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../common/fetch-data.php';
		$fetch_data = new ATTP_Fetch_Data();
		$data = $fetch_data->get_history("addr1qyj7y9d95zdqycpxsu9kyqjxzm7nd0gk2jrqjrnmu9hemclcu29trzjfe76v3y7xvy0lq78k9shqjgptnal59yszj6lstgxl55");

        $data = json_decode( $data, true );

		$columns = array(
            'amount' => 'Amount',
            'tx_hash' => 'Transaction Hash',
        );

		// for($i = 0; $i < count($data['rows']); $i++){
		// 	$data['rows'][$i]['amount'] = 
		// }

  		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../common/Custom_Table_List.php';
		$receiving_addresses_table = new Custom_Table_List($data['rows'], $columns, 15);
		$receiving_addresses_table->prepare_items();

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/account/transactions.php';
	}

    
}