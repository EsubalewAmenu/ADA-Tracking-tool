<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Att_public
 * @subpackage Att_public/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Att_public
 * @subpackage Att_public/public
 * @author     Esubalew Amenu <esubalew.a2009@gmail.com>
 */
class Att_public_transactions
{


	public function att_transaction_history_OnClick()
	{

                ob_start();
                
                wp_enqueue_style('att-transaction-history-style', plugin_dir_url(__FILE__) . '../css/att-public-transaction-history.css', false, '1.0', 'all');

                include_once plugin_dir_path(dirname(__FILE__)) . 'partials/user-transactions.php';
        
                return ob_get_clean();

	}

        public function wp_ajax_load_transaction_history(){
                $ada_address = $_POST['ada_address'];
                $page = $_POST['page'];


                require_once plugin_dir_path( dirname( __FILE__ ) ) . '/../common/fetch-data.php';
                $fetch_data = new Fetch_Data();
                $data = $fetch_data->get_history($ada_address);

                print_r($data);
                die();
        }

}