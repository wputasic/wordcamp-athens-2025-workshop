<?php
/**
 * Register meta fields for REST API
 */

// Register speaker name meta field
register_post_meta('wc_note', 'speaker_name', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function() {
        return current_user_can('edit_posts');
    },
));

// Register session time meta field
register_post_meta('wc_note', 'session_time', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function() {
        return current_user_can('edit_posts');
    },
));

// Register room number meta field
register_post_meta('wc_note', 'room_number', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
    'auth_callback' => function() {
        return current_user_can('edit_posts');
    },
));

