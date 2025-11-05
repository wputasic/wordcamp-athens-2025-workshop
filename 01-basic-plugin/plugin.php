<?php
/**
 * Plugin Name: WordCamp Notes
 * Plugin URI: https://github.com/yourusername/wordcamp-athens-2025-workshop
 * Description: Take and organize notes during WordCamp sessions. A practical plugin for conference attendees.
 * Version: 1.0.0
 * Author: WordCamp Athens 2025 Workshop
 * Author URI: https://athens.wordcamp.org/2025
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wc-notes
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register WordCamp Notes Custom Post Type
 */
function wc_notes_register_post_type() {
    $labels = array(
        'name'                  => _x('WordCamp Notes', 'Post type general name', 'wc-notes'),
        'singular_name'         => _x('Note', 'Post type singular name', 'wc-notes'),
        'menu_name'             => _x('WordCamp Notes', 'Admin Menu text', 'wc-notes'),
        'name_admin_bar'        => _x('Note', 'Add New on Toolbar', 'wc-notes'),
        'add_new'               => __('Add New', 'wc-notes'),
        'add_new_item'          => __('Add New Note', 'wc-notes'),
        'new_item'              => __('New Note', 'wc-notes'),
        'edit_item'             => __('Edit Note', 'wc-notes'),
        'view_item'             => __('View Note', 'wc-notes'),
        'all_items'             => __('All Notes', 'wc-notes'),
        'search_items'          => __('Search Notes', 'wc-notes'),
        'not_found'             => __('No notes found.', 'wc-notes'),
        'not_found_in_trash'    => __('No notes found in Trash.', 'wc-notes'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'wc-note'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-edit',
        'supports'           => array('title', 'editor', 'custom-fields', 'author', 'thumbnail'),
        'show_in_rest'       => false, // We'll enable this in example 05
    );

    register_post_type('wc_note', $args);
}
add_action('init', 'wc_notes_register_post_type');

/**
 * Flush rewrite rules on activation
 */
function wc_notes_activate() {
    wc_notes_register_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wc_notes_activate');

/**
 * Flush rewrite rules on deactivation
 */
function wc_notes_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wc_notes_deactivate');

