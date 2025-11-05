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
    // GET /wp-json/wc-notes/v1/notes - List all notes
    register_rest_route('wc-notes/v1', '/notes', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'wc_notes_get_notes',
        'permission_callback' => '__return_true',
        'args' => array(
            'page' => array(
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'default' => 10,
                'sanitize_callback' => 'absint',
                'validate_callback' => function($param) {
                    return $param <= 100;
                },
            ),
            'search' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'session' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'date' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // POST /wp-json/wc-notes/v1/notes - Create note
    register_rest_route('wc-notes/v1', '/notes', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'wc_notes_create_note',
        'permission_callback' => 'wc_notes_check_permissions',
        'args' => array(
            'title' => array(
                'required' => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'content' => array(
                'required' => true,
                'sanitize_callback' => 'wp_kses_post',
            ),
            'session_title' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'speaker' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'session_time' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // GET /wp-json/wc-notes/v1/notes/{id} - Get single note
    register_rest_route('wc-notes/v1', '/notes/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'wc_notes_get_single_note',
        'permission_callback' => '__return_true',
    ));

    // PUT /wp-json/wc-notes/v1/notes/{id} - Update note
    register_rest_route('wc-notes/v1', '/notes/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'wc_notes_update_note',
        'permission_callback' => 'wc_notes_check_permissions',
        'args' => array(
            'title' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'content' => array(
                'sanitize_callback' => 'wp_kses_post',
            ),
            'session_title' => array(
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    // DELETE /wp-json/wc-notes/v1/notes/{id} - Delete note
    register_rest_route('wc-notes/v1', '/notes/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'wc_notes_delete_note',
        'permission_callback' => 'wc_notes_check_permissions',
    ));
}
add_action('rest_api_init', 'wc_notes_register_rest_routes');

/**
 * Check user permissions
 */
function wc_notes_check_permissions($request) {
    return current_user_can('edit_posts');
}

/**
 * Get all notes
 */
function wc_notes_get_notes($request) {
    $args = array(
        'post_type' => 'wc_note',
        'post_status' => 'publish',
        'posts_per_page' => $request->get_param('per_page'),
        'paged' => $request->get_param('page'),
    );

    // Search
    if ($request->get_param('search')) {
        $args['s'] = $request->get_param('search');
    }

    // Date filter
    if ($request->get_param('date')) {
        $args['date_query'] = array(
            array(
                'year' => substr($request->get_param('date'), 0, 4),
                'month' => substr($request->get_param('date'), 5, 2),
                'day' => substr($request->get_param('date'), 8, 2),
            ),
        );
    }

    $query = new WP_Query($args);
    $notes = array();

    foreach ($query->posts as $post) {
        $notes[] = wc_notes_prepare_note_response($post);
    }

    $response = new WP_REST_Response($notes, 200);
    $response->header('X-WP-Total', $query->found_posts);
    $response->header('X-WP-TotalPages', $query->max_num_pages);

    return $response;
}

/**
 * Get single note
 */
function wc_notes_get_single_note($request) {
    $id = (int) $request->get_param('id');
    $post = get_post($id);

    if (!$post || $post->post_type !== 'wc_note') {
        return new WP_Error('rest_note_invalid_id', __('Invalid note ID.', 'wc-notes'), array('status' => 404));
    }

    return wc_notes_prepare_note_response($post);
}

/**
 * Create note
 */
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

    // Save meta fields
    if ($request->get_param('session_title')) {
        update_post_meta($post_id, 'session_title', $request->get_param('session_title'));
    }
    if ($request->get_param('speaker')) {
        update_post_meta($post_id, 'speaker', $request->get_param('speaker'));
    }
    if ($request->get_param('session_time')) {
        update_post_meta($post_id, 'session_time', $request->get_param('session_time'));
    }

    $post = get_post($post_id);
    $response = wc_notes_prepare_note_response($post);
    $response = rest_ensure_response($response);
    $response->set_status(201);

    return $response;
}

/**
 * Update note
 */
function wc_notes_update_note($request) {
    $id = (int) $request->get_param('id');
    $post = get_post($id);

    if (!$post || $post->post_type !== 'wc_note') {
        return new WP_Error('rest_note_invalid_id', __('Invalid note ID.', 'wc-notes'), array('status' => 404));
    }

    $args = array('ID' => $id);

    if ($request->get_param('title')) {
        $args['post_title'] = $request->get_param('title');
    }
    if ($request->get_param('content')) {
        $args['post_content'] = $request->get_param('content');
    }

    $updated = wp_update_post($args);

    if (is_wp_error($updated)) {
        return $updated;
    }

    // Update meta fields
    if ($request->get_param('session_title')) {
        update_post_meta($id, 'session_title', $request->get_param('session_title'));
    }

    $post = get_post($id);
    return wc_notes_prepare_note_response($post);
}

/**
 * Delete note
 */
function wc_notes_delete_note($request) {
    $id = (int) $request->get_param('id');
    $post = get_post($id);

    if (!$post || $post->post_type !== 'wc_note') {
        return new WP_Error('rest_note_invalid_id', __('Invalid note ID.', 'wc-notes'), array('status' => 404));
    }

    $force = (bool) $request->get_param('force');
    $result = wp_delete_post($id, $force);

    if (!$result) {
        return new WP_Error('rest_cannot_delete', __('The note cannot be deleted.', 'wc-notes'), array('status' => 500));
    }

    $response = new WP_REST_Response(null, 204);
    return $response;
}

/**
 * Prepare note response
 */
function wc_notes_prepare_note_response($post) {
    return array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
        'session_title' => get_post_meta($post->ID, 'session_title', true),
        'speaker' => get_post_meta($post->ID, 'speaker', true),
        'session_time' => get_post_meta($post->ID, 'session_time', true),
        'date' => mysql2date('c', $post->post_date),
        'modified' => mysql2date('c', $post->post_modified),
        'link' => get_permalink($post->ID),
    );
}

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

