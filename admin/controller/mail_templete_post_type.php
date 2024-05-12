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
class ATTP_mail_templete_post_type_Admin
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

    function attp_mail_templete_format_init()
    {

        $labels = array(
            'name'                  => _x('Email Templetes', 'Post type general name', 'ADA trackings'),
            'singular_name'         => _x('ADA tracking', 'Post type singular name', 'ADA tracking'),
            'menu_name'             => _x('ADA trackings', 'Admin Menu text', 'ADA trackings'),
            'name_admin_bar'        => _x('ADA trackings', 'Add New on Toolbar', 'ADA trackings'),
            'add_new'               => __('New Email Templete', 'ADA trackings'),
            'add_new_item'          => __('Add New ADA tracking', 'ADA trackings'),
            'new_item'              => __('New ADA tracking', 'ADA trackings'),
            'edit_item'             => __('Edit ADA tracking', 'ADA trackings'),
            'view_item'             => __('View ADA tracking', 'ADA trackings'),
            'all_items'             => __('All Email Templetes', 'ADA trackings'),
            'search_items'          => __('Search ADA trackings', 'ADA trackings'),
            'parent_item_colon'     => __('Parent ADA trackings:', 'ADA trackings'),
            'not_found'             => __('No ADA trackings found.', 'ADA trackings'),
            'not_found_in_trash'    => __('No ADA trackings found in Trash.', 'ADA trackings'),
            'featured_image'        => _x('ADA tracking Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'general_plugin'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'general_plugin'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'general_plugin'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'general_plugin'),
            'archives'              => _x('ADA tracking archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'general_plugin'),
            'insert_into_item'      => _x('Insert into ADA tracking', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'general_plugin'),
            'uploaded_to_this_item' => _x('Uploaded to this ADA tracking', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'general_plugin'),
            'filter_items_list'     => _x('Filter ADA trackings list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'general_plugin'),
            'items_list_navigation' => _x('ADA tracking list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'general_plugin'),
            'items_list'            => _x('ADA tracking list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'general_plugin'),
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


}
