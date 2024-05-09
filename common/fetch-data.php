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

 class ATTP_Fetch_Data {
    private $base_url = 'https://adastat.net/api/rest/v1/';

    function get_history($address) {
        $params = ".json?rows=history&dir=desc&limit=24&currency=usd";
        $api_url = $this->base_url . "addresses/" . $address . $params;
    
        // Make the request
        $response = wp_remote_get( $api_url );
    
        // Check for errors
        if ( is_wp_error( $response ) ) {
            return false; // Handle error accordingly
        }
    
        // Get the response body
        $body = wp_remote_retrieve_body( $response );
    
        // Return the data
        return $body;
    }

    function formatMoney($amount, $decimalPoint) {
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