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

class Attp_Fetch_Data
{
    private $blockfrost_base_url = 'https://cardano-mainnet.blockfrost.io/api/v0/';
    private $adastat_base_url = 'https://adastat.net/api/rest/v1/';

    function fetch_cardano_transactions($address, $count, $page, $order, $block = null)
    {
        $url = $this->blockfrost_base_url . "addresses/$address/transactions?count=$count&page=$page&order=$order";

        // Append the 'from' parameter only if $block is not null
        if ($block !== null) {
            $url .= "&from=$block";
        }

        $name = "attp_blockfrost_api";
        $options = get_option('attp_option');
        $api_key = isset($options[$name]) ? esc_attr($options[$name]) : '';

        $args = array(
            'headers' => array(
                'project_id' => $api_key
            )
        );

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return 'Error retrieving transactions: ' . $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return 'No transactions found for the specified period.';
        }

        return $body;
    }
    function get_transactions($receiving_address, $count, $page, $order, $block = null)
    {

        $data = self::fetch_cardano_transactions($receiving_address, $count, $page, $order, $block);

        if ($data = json_decode($data, true)) {
            for ($i = 0; $i < sizeof($data); $i++) {

                $transaction_data = self::fetch_transaction_details($data[$i]['tx_hash']);

                $transaction_data = json_decode($transaction_data, true);
                $result = self::determine_transaction_type($transaction_data, $receiving_address);

                $token_amounts = "";
                for ($j = 0; $j < sizeof($result['transaction_tokens']); $j++) {
                    if ($token_amounts != "") {
                        $token_amounts .= "<br>";
                    }
                    if ($result['is_incoming']) {
                        $token_amounts .= self::formatMoney($result['transaction_tokens'][$j]['amount'], $result['transaction_tokens'][$j]['decimals']) . ' ' . $result['transaction_tokens'][$j]['ticker'];
                    } else {
                        $token_amounts .= '-' . self::formatMoney($result['transaction_tokens'][$j]['amount'], $result['transaction_tokens'][$j]['decimals']) . ' ' . $result['transaction_tokens'][$j]['ticker'];
                    }
                }

                $data[$i]['is_incoming'] = $result['is_incoming'] ? "true" : "false";
                $data[$i]['amount'] = $token_amounts;
                $data[$i]['tx_hash'] = $data[$i]['tx_hash'];
                $data[$i]['time'] = $result['transaction_time'];
                $data[$i]['message'] = $result['message'];
                $data[$i]['confirmation'] = $result['confirmation'];
            }
        }
        return $data;
    }

    function fetch_transaction_details($tx_hash)
    {
        $url = $this->adastat_base_url . "transactions/$tx_hash.json?currency=usd";

        $args = array(
            'headers' => array()
        );

        $response = wp_remote_get($url, $args);
        if (is_wp_error($response)) {
            return 'Error retrieving transaction details: ' . $response->get_error_message();
        }

        $body = wp_remote_retrieve_body($response);

        return $body;
    }



    function determine_transaction_type($transaction_data, $my_address)
    {
        $inputs = $transaction_data['data']['inputs']['rows'];
        $outputs = $transaction_data['data']['outputs']['rows'];
        $metadata = $transaction_data['data']['metadata']['rows'];

        $transaction_details = [
            'is_incoming' => false,
            'transaction_time' => gmdate('Y-m-d H:i:s', $transaction_data['data']['time']),
            'tx_hash' => $transaction_data['data']['hash'],
            'transaction_tokens' => [],
            'confirmation' => $transaction_data['data']['confirmation'],
            'message' => null

        ];

        // Extracting "msg" from metadata if available
        foreach ($metadata as $data) {
            if (isset($data['data']['msg'])) {
                $transaction_details['message'] = implode(", ", $data['data']['msg']); // Join messages if there are multiple
                break; // Stop after finding the first relevant message
            }
        }

        // Check for outgoing transactions by seeing if the address is in the inputs
        $is_outgoing = false;
        foreach ($inputs as $input) {
            if ($input['address'] === $my_address) {
                $is_outgoing = true;
                break;
            }
        }

        // If the address is only in inputs, it's purely an outgoing transaction
        if (!$is_outgoing) {
            $transaction_details['is_incoming'] = true;
        }

        // Check for incoming transactions and gather token details
        foreach ($outputs as $output) {
            if ($is_outgoing) {

                if ($output['address'] !== $my_address) {
                    // Check if tokens are involved in the output
                    if ($output['token']) {
                        foreach ($output['tokens'] as $token) {
                            $transaction_details['transaction_tokens'][] = [
                                'ticker' => $token['ticker'] ?? 'ADA',
                                'amount' => $token['quantity'],
                                'decimals' => $token['decimals'] ?? 0
                            ];
                        }
                    }
                    $transaction_details['transaction_tokens'][] = [
                        'ticker' => 'ADA',
                        'amount' => $output['amount'],
                        'decimals' => 6  // ADA has 6 decimal places
                    ];
                }
            } else {
                if ($output['address'] === $my_address) {
                    // Check if tokens are involved in the output
                    if ($output['token']) {
                        foreach ($output['tokens'] as $token) {
                            $transaction_details['transaction_tokens'][] = [
                                'ticker' => $token['ticker'] ?? 'ADA',
                                'amount' => $token['quantity'],
                                'decimals' => $token['decimals'] ?? 0
                            ];
                        }
                    }
                    $transaction_details['transaction_tokens'][] = [
                        'ticker' => 'ADA',
                        'amount' => $output['amount'],
                        'decimals' => 6  // ADA has 6 decimal places
                    ];
                }
            }
        }


        return $transaction_details;
    }




    function formatMoney($amount, $decimalPoint)
    {
        // Find index of the decimal point
        $decimalIndex = strlen($amount) - $decimalPoint;
        // Extract the integer part and the decimal part
        $integerPart = substr($amount, 0, $decimalIndex);
        $decimalPart = substr($amount, $decimalIndex);

        // Insert commas in the integer part
        $formattedIntegerPart = number_format($integerPart, 0, '', ',');

        // Remove trailing zeros from the decimal part and add a decimal point
        $formattedDecimalPart = "." . rtrim($decimalPart, "0");

        // Concatenate the integer part and decimal part
        $formattedAmount = $formattedIntegerPart . $formattedDecimalPart;

        return $formattedAmount;
    }
}
