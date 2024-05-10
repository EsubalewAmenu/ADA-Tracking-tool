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


                if (isset($_GET['count']) && !empty(esc_attr($_GET['count']))) {
                        $attp_tx_per_page = esc_attr($_GET['count']);
                } else {
                        $name = "attp_tx_per_page";
                        $options = get_option('ada_tracking_option');
                        $attp_tx_per_page = isset($options[$name]) ? esc_attr($options[$name]) : 5;
                }

                include_once plugin_dir_path(dirname(__FILE__)) . 'partials/user-transactions.php';

                return ob_get_clean();
        }

        public function wp_ajax_load_transaction_history()
        {
                check_ajax_referer('load_transaction_history_nonce', 'security');


                $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
                $ada_address = isset($_POST['ada_address']) ? sanitize_text_field($_POST['ada_address']) : "";
                $count = isset($_POST['count']) ? intval($_POST['count']) : 1;

                $order = 'desc';

                require_once plugin_dir_path(dirname(__FILE__)) . '/../common/fetch-data.php';
                $fetch_data = new ATTP_Fetch_Data();
                $data = $fetch_data->get_transactions($ada_address, $count, $page, $order);

                print_r(json_encode($data));
                die();
        }
}
