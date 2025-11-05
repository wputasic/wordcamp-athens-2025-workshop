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

// Load admin files
require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';

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
 * Register WordCamp Notes Custom Post Type
 */
function wc_notes_register_post_type() {
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
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-edit',
        'supports'           => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('wc_note', $args);
}
add_action('init', 'wc_notes_register_post_type');

/**
 * Register REST API routes
 */
function wc_notes_register_rest_routes() {
    register_rest_route('wc-notes/v1', '/notes', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'wc_notes_get_notes',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('wc-notes/v1', '/notes', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'wc_notes_create_note',
        'permission_callback' => 'wc_notes_check_permissions',
    ));

    register_rest_route('wc-notes/v1', '/notes/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'wc_notes_update_note',
        'permission_callback' => 'wc_notes_check_permissions',
    ));

    register_rest_route('wc-notes/v1', '/notes/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'wc_notes_delete_note',
        'permission_callback' => 'wc_notes_check_permissions',
    ));
}
add_action('rest_api_init', 'wc_notes_register_rest_routes');

function wc_notes_check_permissions($request) {
    return current_user_can('edit_posts');
}

function wc_notes_get_notes($request) {
    $args = array(
        'post_type' => 'wc_note',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $notes = array();

    foreach ($query->posts as $post) {
        $notes[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'date' => mysql2date('c', $post->post_date),
        );
    }

    return rest_ensure_response($notes);
}

function wc_notes_create_note($request) {
    $args = array(
        'post_type' => 'wc_note',
        'post_title' => $request->get_param('title'),
        'post_content' => $request->get_param('content'),
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($args);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $post = get_post($post_id);
    return rest_ensure_response(array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
    ));
}

function wc_notes_update_note($request) {
    $id = (int) $request->get_param('id');
    $args = array(
        'ID' => $id,
        'post_title' => $request->get_param('title'),
        'post_content' => $request->get_param('content'),
    );

    $updated = wp_update_post($args);
    if (is_wp_error($updated)) {
        return $updated;
    }

    $post = get_post($id);
    return rest_ensure_response(array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
    ));
}

function wc_notes_delete_note($request) {
    $id = (int) $request->get_param('id');
    $result = wp_delete_post($id, true);

    if (!$result) {
        return new WP_Error('rest_cannot_delete', __('The note cannot be deleted.', 'wc-notes'), array('status' => 500));
    }

    return rest_ensure_response(array('deleted' => true));
}

/**
 * Enqueue admin scripts and styles
 */
function wc_notes_enqueue_admin_assets($hook) {
    if ('toplevel_page_wc-notes-manage' !== $hook) {
        return;
    }

    wp_enqueue_style(
        'wc-notes-admin',
        plugin_dir_url(__FILE__) . 'assets/admin.css',
        array(),
        '1.0.0'
    );

    wp_enqueue_script(
        'wc-notes-admin',
        plugin_dir_url(__FILE__) . 'assets/admin.js',
        array('jquery'),
        '1.0.0',
        true
    );

    wp_localize_script('wc-notes-admin', 'wcNotesData', array(
        'restUrl' => rest_url('wc-notes/v1/'),
        'nonce' => wp_create_nonce('wp_rest'),
    ));
}
add_action('admin_enqueue_scripts', 'wc_notes_enqueue_admin_assets');

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

