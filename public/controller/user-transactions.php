<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    Attp_public
 * @subpackage Attp_public/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Attp_public
 * @subpackage Attp_public/public
 * @author     Esubalew Amenu <esubalew.a2009@gmail.com>
 */
class Attp_public_transactions
{


        public function attp_transaction_history_OnClick()
        {

                ob_start();

                wp_enqueue_style('attp-transaction-history-style', plugin_dir_url(__FILE__) . '../css/attp-public-transaction-history.css', false, '1.0', 'all');

                $name = "attp_tx_per_page";
                $options = get_option('attp_option');
                $attp_tx_per_page = isset($options[$name]) ? esc_attr($options[$name]) : 5;

                if (isset($_GET['count'], $_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'], 'count_nonce')))) {
                        if (!empty($_GET['count'])) {
                                $attp_tx_per_page = esc_attr($_GET['count']);
                        }
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
                $Attp_Fetch_Data = new Attp_Fetch_Data();
                $data = $Attp_Fetch_Data->get_transactions($ada_address, $count, $page, $order);

                print_r(wp_json_encode($data));
                die();
        }
}
