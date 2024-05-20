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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
 
 class Attp_Custom_Table_List extends WP_List_Table {
     private $data = array(); // Class property to store data
     private $columns;
     private $per_page = 10;
 
     function __construct($data, $columns, $per_page) {
         parent::__construct(array(
             'singular' => 'receiving_address',
             'plural' => 'receiving_addresses',
             'ajax' => false
         ));
 
         $this->data = $data;
         $this->columns = $columns;
         $this->per_page = $per_page;
     }
 
     function get_columns() {
         return $this->columns;
     }
 
     function prepare_items() {
 
         $this->_column_headers = array($this->get_columns(), array(), array());
         $this->items = $this->data; // Access data from the class property
 
         $total_items = count($this->data);
         $current_page = $this->get_pagenum();
         $this->set_pagination_args(array(
             'total_items' => $total_items,
             'per_page'    => $this->per_page
         ));
 
         $this->items = array_slice($this->data, (($current_page - 1) * $this->per_page), $this->per_page);
     }
 
     function column_default($item, $column_name) {
         // Handle default column output
         return isset($item[$column_name]) ? $item[$column_name] : '';
     }
 }