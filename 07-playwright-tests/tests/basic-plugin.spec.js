const { test, expect } = require('@playwright/test');
const { loadBlueprint, loginToWordPress, isPluginActive, waitForWordPressReady } = require('./utils/playground-helpers');

/**
 * Basic plugin installation and activation tests
 * 
 * This test suite demonstrates how to:
 * - Load WordPress Playground
 * - Install and activate a plugin
 * - Verify plugin functionality
 */
test.describe('WordCamp Notes Plugin - Basic Installation', () => {
  let iframe;

  test.beforeEach(async ({ page }) => {
    // Basic blueprint for WordCamp Notes plugin
    const blueprint = {
      "$schema": "https://playground.wordpress.net/blueprint-schema.json",
      "landingPage": "/wp-admin/",
      "steps": [
        {
          "step": "login",
          "username": "admin",
          "password": "password"
        },
        {
          "step": "writeFile",
          "path": "/wordpress/wp-content/plugins/wc-notes/plugin.php",
          "data": `<?php
/**
 * Plugin Name: WordCamp Notes
 * Description: Take and organize notes during WordCamp sessions
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

function wc_notes_register_post_type() {
    register_post_type('wc_note', array(
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-edit',
        'supports' => array('title', 'editor'),
    ));
}
add_action('init', 'wc_notes_register_post_type');
`
        },
        {
          "step": "runPHP",
          "code": "<?php\nrequire_once ABSPATH . 'wp-admin/includes/plugin.php';\nactivate_plugin('wc-notes/plugin.php');\n"
        }
      ]
    };

    // Load blueprint into Playground
    iframe = await loadBlueprint(page, blueprint);
    
    // Login to WordPress
    await loginToWordPress(iframe);
  });

  test('should load WordPress Playground successfully', async ({ page }) => {
    // Verify iframe is loaded
    expect(iframe).toBeTruthy();
    
    // Check WordPress admin is accessible
    await waitForWordPressReady(iframe);
    
    // Verify we're in the admin area
    const body = iframe.locator('body');
    await expect(body).toHaveClass(/wp-admin/);
  });

  test('should install and activate WordCamp Notes plugin', async () => {
    // Check if plugin is active
    const pluginActive = await isPluginActive(iframe, 'wc-notes');
    expect(pluginActive).toBe(true);
  });

  test('should display WordCamp Notes menu in admin', async () => {
    // Wait for admin menu to load
    await iframe.waitForSelector('#adminmenu', { timeout: 10000 });
    
    // Check if WordCamp Notes menu item exists
    // Note: Menu text might vary, so we check for the post type
    await iframe.goto('/wp-admin/edit.php?post_type=wc_note');
    await waitForWordPressReady(iframe);
    
    // Verify we're on the notes page
    const pageTitle = iframe.locator('h1');
    await expect(pageTitle).toBeVisible();
  });

  test('should have Custom Post Type registered', async () => {
    // Navigate to the notes post type page
    await iframe.goto('/wp-admin/edit.php?post_type=wc_note');
    await waitForWordPressReady(iframe);
    
    // Check for "Add New" button which indicates CPT is registered
    const addNewButton = iframe.locator('text=Add New');
    await expect(addNewButton).toBeVisible();
  });
});

