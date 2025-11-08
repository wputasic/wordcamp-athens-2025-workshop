<?php
/**
 * Plugin Name: Workshop WordCamp Athens 2025
 * Plugin URI: https://github.com/yourusername/wordcamp-athens-2025-workshop
 * Description: Example of a Dashboard widget
 * Version: 0.0.1
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: Uros Tasic
 * Author URI: https://athens.wordcamp.org/2025
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
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
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function wc_notes_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'wc_notes_dashboard_widget',                          // Widget slug.
		esc_html__( 'Welcome to Playground!', 'wc-notes' ), // Title.
		'wc_notes_dashboard_widget_render'                    // Display function.
	); 
    // Globalize the metaboxes array, this holds all the widgets for wp-admin.
	global $wp_meta_boxes;
	
	// Get the regular dashboard widgets array 
	// (which already has our new widget but appended at the end).
	$default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
	
	// Backup and delete our new dashboard widget from the end of the array.
	$wc_notes_dashboard_backup = array( 'wc_notes_dashboard_widget' => $default_dashboard['wc_notes_dashboard_widget'] );
	unset( $default_dashboard['wc_notes_dashboard_widget'] );
 
	// Merge the two arrays together so our widget is at the beginning.
	$sorted_dashboard = array_merge( $wc_notes_dashboard_backup, $default_dashboard );
 
	// Save the sorted array back into the original metaboxes. 
	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'wc_notes_add_dashboard_widgets' );

/**
 * Create the function to output the content of our Dashboard Widget.
 */
function wc_notes_dashboard_widget_render() {
    $user_id = get_current_user_id();
    $meta_key = 'wc_notes_dashboard_note';
    $message = '';

    // Handle form submission
    if ( isset($_POST['wc_notes_note_nonce']) && wp_verify_nonce($_POST['wc_notes_note_nonce'], 'wc_notes_save_note') ) {
        if ( isset($_POST['wc_notes_note']) ) {
            $note = sanitize_textarea_field($_POST['wc_notes_note']);
            update_user_meta($user_id, $meta_key, $note);
            $message = '<div style="color:green;">' . esc_html__( 'Note saved!', 'wc-notes' ) . '</div>';
        }
    }

    $note = get_user_meta($user_id, $meta_key, true);
    ?>
    <?php echo $message; ?>
    <form method="post">
        <label for="wc_notes_note"><?php esc_html_e( 'Quick Note (only you can see this):', 'wc-notes' ); ?></label><br>
        <textarea name="wc_notes_note" id="wc_notes_note" rows="3" cols="40"><?php echo esc_textarea($note); ?></textarea><br>
        <?php wp_nonce_field('wc_notes_save_note', 'wc_notes_note_nonce'); ?>
        <input type="submit" value="<?php esc_attr_e( 'Save Note', 'wc-notes' ); ?>" class="button button-primary">
    </form>
    <?php
}
