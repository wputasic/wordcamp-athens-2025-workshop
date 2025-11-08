const { test, expect } = require('@playwright/test');
const { loadBlueprint, loginToWordPress, navigateToAdminPage, waitForWordPressReady } = require('./utils/playground-helpers');

/**
 * Admin interface interaction tests
 * 
 * This test suite demonstrates how to:
 * - Navigate admin pages
 * - Create content
 * - Verify content appears
 */
test.describe('WordCamp Notes Plugin - Admin Interface', () => {
  let iframe;

  test.beforeEach(async ({ page }) => {
    // Basic blueprint with plugin
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

    iframe = await loadBlueprint(page, blueprint);
    await loginToWordPress(iframe);
  });

  test('should navigate to notes list page', async () => {
    await navigateToAdminPage(iframe, 'edit.php?post_type=wc_note');
    
    // Verify page loaded
    const pageTitle = iframe.locator('h1');
    await expect(pageTitle).toBeVisible();
  });

  test('should be able to create a new note', async () => {
    // Navigate to add new note page
    await navigateToAdminPage(iframe, 'post-new.php?post_type=wc_note');
    
    // Wait for editor to load
    await iframe.waitForSelector('#title', { timeout: 10000 });
    
    // Fill in title
    await iframe.locator('#title').fill('Test Note from Playwright');
    
    // Fill in content (using the classic editor or block editor)
    // Try classic editor first
    const editorContent = iframe.locator('#content').or(iframe.locator('.block-editor-rich-text__editable'));
    if (await editorContent.count() > 0) {
      await editorContent.first().fill('This is a test note created by Playwright automation.');
    }
    
    // Click publish button
    const publishButton = iframe.locator('#publish').or(iframe.locator('button:has-text("Publish")'));
    await publishButton.click();
    
    // Wait for success message
    await iframe.waitForSelector('.notice-success, .components-notice.is-success', { timeout: 10000 });
    
    // Verify note was created - navigate back to list
    await navigateToAdminPage(iframe, 'edit.php?post_type=wc_note');
    
    // Check if our note appears in the list
    const noteTitle = iframe.locator('text=Test Note from Playwright');
    await expect(noteTitle).toBeVisible({ timeout: 5000 });
  });

  test('should display notes list correctly', async () => {
    await navigateToAdminPage(iframe, 'edit.php?post_type=wc_note');
    
    // Check for the posts table
    const postsTable = iframe.locator('.wp-list-table').or(iframe.locator('table'));
    await expect(postsTable).toBeVisible();
    
    // Check for "Add New" button
    const addNewButton = iframe.locator('text=Add New');
    await expect(addNewButton).toBeVisible();
  });
});

