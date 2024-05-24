<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/EsubalewAmenu
 * @since      1.0.0
 *
 * @package    ATTP_mail
 * @subpackage ATTP_mail/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ATTP_mail
 * @subpackage ATTP_mail/admin
 * @author     Esubalew A. <esubalew.amenu@singularitynet.io>
 */
class ATTP_mail_template_post_type_Admin
{

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */

    public function __construct()
    {
    }

    function attp_mail_template_format_init()
    {

        $labels = array(
            'name'                  => _x('Email Templates', 'Post type general name', 'ada-tracking-tool'),
            'singular_name'         => _x('ADA tracking', 'Post type singular name', 'ada-tracking-tool'),
            'menu_name'             => _x('ADA trackings', 'Admin Menu text', 'ada-tracking-tool'),
            'name_admin_bar'        => _x('ADA trackings', 'Add New on Toolbar', 'ada-tracking-tool'),
            'add_new'               => __('New Email Template', 'ada-tracking-tool'),
            'add_new_item'          => __('Add New ADA tracking', 'ada-tracking-tool'),
            'new_item'              => __('New ADA tracking', 'ada-tracking-tool'),
            'edit_item'             => __('Edit ADA tracking', 'ada-tracking-tool'),
            'view_item'             => __('View ADA tracking', 'ada-tracking-tool'),
            'all_items'             => __('All Email Templates', 'ada-tracking-tool'),
            'search_items'          => __('Search ADA trackings', 'ada-tracking-tool'),
            'parent_item_colon'     => __('Parent ADA trackings:', 'ada-tracking-tool'),
            'not_found'             => __('No ADA trackings found.', 'ada-tracking-tool'),
            'not_found_in_trash'    => __('No ADA trackings found in Trash.', 'ada-tracking-tool'),
            'featured_image'        => _x('ADA tracking Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'ada-tracking-tool'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'ada-tracking-tool'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'ada-tracking-tool'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'ada-tracking-tool'),
            'archives'              => _x('ADA tracking archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'ada-tracking-tool'),
            'insert_into_item'      => _x('Insert into ADA tracking', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'ada-tracking-tool'),
            'uploaded_to_this_item' => _x('Uploaded to this ADA tracking', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'ada-tracking-tool'),
            'filter_items_list'     => _x('Filter ADA trackings list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'ada-tracking-tool'),
            'items_list_navigation' => _x('ADA tracking list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'ada-tracking-tool'),
            'items_list'            => _x('ADA tracking list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'ada-tracking-tool'),
        );
        $args = array(
            'labels'             => $labels,
            'description'        => 'ADA tracking custom post type.',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'attp_mails'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'   => 'dashicons-book',
            'supports'           => array('title', 'editor', 'author'),
            // 'taxonomies'         => array('category', 'post_tag'),
            'show_in_rest'       => true
        );

        register_post_type('attp_mails', $args);
    }


    public function template($email, $slug, $transactions, $bodyReplacements)
    {
        $args = array(
            'name' => $slug,
            'post_type' => array('attp_mails'),
            'post_status' => 'publish',
            'showposts' => 1,
            'ignore_sticky_posts' => 1,
        );
        $my_posts = get_posts($args);

        if ($my_posts) {
            $subject = $my_posts[0]->post_title;
            $content_title = $my_posts[0]->post_title;

            $body = $my_posts[0]->post_content;

            foreach ($bodyReplacements as $key => $value) {
                $subject = str_replace("{{--" . $key . "--}}", $value, html_entity_decode($subject));
                $content_title = str_replace("{{--" . $key . "--}}", $value, $content_title);
                $body = str_replace("{{--" . $key . "--}}", $value, $body);
            }

            if (strpos($body, '{{--transaction_list--}}') !== false) {


                $body = str_replace("{{--transaction_list--}}", self::transactions_div($transactions), $body);
            }

            $file_path = plugin_dir_path(dirname(__FILE__)) . 'partials/account/template.php';
            $email_content = file_get_contents($file_path);

            $email_content = str_replace("{{--subject--}}", $subject, $email_content);
            $email_content = str_replace("{{--content_title--}}", "<p style='margin-top: -10px;margin-left: 48px;color: #49FFB3;font-size:15px !important'>Where the future gets [sur]real", $email_content);
            $email_content = str_replace("{{--body--}}", $body, $email_content);

            $email_content = str_replace("{{--home_url--}}", home_url(), $email_content);

            $email_content = str_replace("{{--twitter--}}", plugin_dir_url(__FILE__)."../../common/images/X-logo.png", $email_content);
            $email_content = str_replace("{{--telegram--}}", plugin_dir_url(__FILE__)."../../common/images/telegram-icon.png", $email_content);
            $email_content = str_replace("{{--linkedin--}}", plugin_dir_url(__FILE__)."../../common/images/linkedin-logo.png", $email_content);

            $email_content = str_replace("{{--site_admin_name--}}", get_bloginfo('name'), $email_content);
            
            $header = array('Content-Type: text/html; charset=UTF-8');

            return wp_mail($email, $subject, $email_content, $header);
        }
        return 0;
    }


    public function transactions_div($transactions)
    {
        $transactions_content = '<table id="transaction-table">
        <thead>
            <tr>
                <th>Tx type</th>
                <th>Amount</th>
                <th>TX hash</th>
                <th>TX time</th>
                <th>Message</th>
                <th>Tx confirmation</th>
            </tr>
        </thead>
        <tbody id="transaction-table-body">';
        foreach ($transactions as $transaction) {

            $transactions_content .= '
            <tr>
            <td>' . ($transaction['is_incoming'] == "true" ? "Incomming Tx" : "Outgoint Tx") . '</td>
            <td>' . $transaction['amount'] . '</td>
        <td><a style="color: #fff !important;text-decoration:none;" href="' . $transaction['tx_hash'] . '">' . substr($transaction['tx_hash'], 0, 15) . '</a></td>
        <td>' . $transaction['time'] . '</td>
        <td>' . $transaction['message'] . '</td>
        <td>' . $transaction['confirmation'] . '</td>
        </tr>';
        }
        $transactions_content .= '</tbody> </table>';
        if ($transactions_content == "") return "";

        return $transactions_content;
    }
}
