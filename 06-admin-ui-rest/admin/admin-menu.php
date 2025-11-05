<?php
/**
 * Register admin menu
 */

function wc_notes_add_admin_menu() {
    add_menu_page(
        __('WordCamp Notes', 'wc-notes'),
        __('WordCamp Notes', 'wc-notes'),
        'edit_posts',
        'wc-notes-manage',
        'wc_notes_admin_page',
        'dashicons-edit',
        30
    );
}
add_action('admin_menu', 'wc_notes_add_admin_menu');

