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

// Load meta fields registration
require_once plugin_dir_path(__FILE__) . 'meta-fields.php';

/**
 * Load plugin text domain for translations
 */
function wc_notes_load_textdomain() {
    load_plugin_textdomain(
        'wc-notes',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}
add_action('plugins_loaded', 'wc_notes_load_textdomain');

/**
 * Register Custom Post Type with REST API support
 */
function wc_notes_register_cpt() {
    $labels = array(
        'name'                  => _x('WordCamp Notes', 'Post type general name', 'wc-notes'),
        'singular_name'         => _x('Note', 'Post type singular name', 'wc-notes'),
        'menu_name'             => _x('WordCamp Notes', 'Admin Menu text', 'wc-notes'),
        'add_new_item'          => __('Add New Note', 'wc-notes'),
        'all_items'             => __('All Notes', 'wc-notes'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true, // Enable REST API
        'rest_base'          => 'wc-note',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'menu_icon'          => 'dashicons-edit',
        'supports'           => array('title', 'editor', 'custom-fields', 'author', 'thumbnail'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'wc-note'),
    );

    register_post_type('wc_note', $args);

    // Register custom taxonomy
    register_taxonomy('session_track', 'wc_note', array(
        'labels' => array(
            'name' => __('Session Tracks', 'wc-notes'),
            'singular_name' => __('Track', 'wc-notes'),
            'add_new_item' => __('Add New Track', 'wc-notes'),
            'edit_item' => __('Edit Track', 'wc-notes'),
        ),
        'public' => true,
        'show_in_rest' => true, // Enable REST API
        'rest_base' => 'session-track',
        'hierarchical' => true,
    ));

    // Register skill level taxonomy
    register_taxonomy('skill_level', 'wc_note', array(
        'labels' => array(
            'name' => __('Skill Levels', 'wc-notes'),
            'singular_name' => __('Skill Level', 'wc-notes'),
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'skill-level',
        'hierarchical' => false,
    ));
}
add_action('init', 'wc_notes_register_cpt');

/**
 * Register custom REST endpoint for statistics
 */
function wc_notes_register_stats_endpoint() {
    register_rest_route('wc-notes/v1', '/stats', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'wc_notes_get_stats',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'wc_notes_register_stats_endpoint');

/**
 * Get plugin statistics
 */
function wc_notes_get_stats() {
    $counts = wp_count_posts('wc_note');
    $total_notes = isset($counts->publish) ? (int) $counts->publish : 0;
    
    $tracks = get_terms(array(
        'taxonomy' => 'session_track',
        'hide_empty' => false,
    ));
    
    $stats = array(
        'total_notes' => $total_notes,
        'total_tracks' => count($tracks),
        'notes_by_status' => array(
            'publish' => isset($counts->publish) ? (int) $counts->publish : 0,
            'draft' => isset($counts->draft) ? (int) $counts->draft : 0,
        ),
    );
    
    return rest_ensure_response($stats);
}

/**
 * Flush rewrite rules on activation
 */
function wc_notes_activate() {
    wc_notes_register_cpt();
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

