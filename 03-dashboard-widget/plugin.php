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
        'show_in_rest'       => false,
    );

    register_post_type('wc_note', $args);
}
add_action('init', 'wc_notes_register_post_type');

/**
 * Add dashboard widget
 */
function wc_notes_dashboard_widget() {
    wp_add_dashboard_widget(
        'wc_notes_dashboard',
        __('WordCamp Notes Summary', 'wc-notes'),
        'wc_notes_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'wc_notes_dashboard_widget');

/**
 * Dashboard widget content
 */
function wc_notes_dashboard_widget_content() {
    // Get note counts
    $counts = wp_count_posts('wc_note');
    $total_notes = isset($counts->publish) ? $counts->publish : 0;
    
    // Get today's notes count
    $today_notes = wc_notes_get_today_count();
    
    // Display statistics
    echo '<div class="wc-notes-dashboard-stats">';
    echo '<p><strong>' . sprintf(
        __('Total Notes: %d', 'wc-notes'),
        $total_notes
    ) . '</strong></p>';
    
    echo '<p>' . sprintf(
        __('Notes from today: %d', 'wc-notes'),
        $today_notes
    ) . '</p>';
    echo '</div>';
    
    // Get recent notes
    $recent_notes = get_posts(array(
        'post_type' => 'wc_note',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    if (!empty($recent_notes)) {
        echo '<h4>' . __('Recent Notes', 'wc-notes') . '</h4>';
        echo '<ul>';
        foreach ($recent_notes as $note) {
            echo '<li>';
            echo '<a href="' . get_edit_post_link($note->ID) . '">';
            echo esc_html($note->post_title);
            echo '</a>';
            echo ' <span class="description">(' . human_time_diff(get_the_time('U', $note->ID), current_time('timestamp')) . ' ' . __('ago', 'wc-notes') . ')</span>';
            echo '</li>';
        }
        echo '</ul>';
    }
    
    // Quick action button
    echo '<p>';
    echo '<a href="' . admin_url('post-new.php?post_type=wc_note') . '" class="button button-primary">';
    echo __('Add New Note', 'wc-notes');
    echo '</a>';
    echo ' <a href="' . admin_url('edit.php?post_type=wc_note') . '" class="button">';
    echo __('View All Notes', 'wc-notes');
    echo '</a>';
    echo '</p>';
}

/**
 * Get today's notes count
 */
function wc_notes_get_today_count() {
    $today = getdate();
    $args = array(
        'post_type' => 'wc_note',
        'posts_per_page' => -1,
        'date_query' => array(
            array(
                'year'  => $today['year'],
                'month' => $today['mon'],
                'day'   => $today['mday'],
            ),
        ),
    );
    
    $query = new WP_Query($args);
    return $query->found_posts;
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

