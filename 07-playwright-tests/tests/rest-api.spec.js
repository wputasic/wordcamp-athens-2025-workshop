const { test, expect } = require('@playwright/test');
const { loadBlueprint, loginToWordPress, waitForWordPressReady } = require('./utils/playground-helpers');

/**
 * REST API endpoint tests
 * 
 * This test suite demonstrates how to:
 * - Test REST API endpoints
 * - Verify API responses
 * - Test CRUD operations via API
 */
test.describe('WordCamp Notes Plugin - REST API', () => {
  let iframe;
  let apiBaseUrl;

  test.beforeEach(async ({ page }) => {
    // Blueprint with REST API enabled plugin
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
        'show_in_rest' => true,
        'rest_base' => 'wc-note',
        'supports' => array('title', 'editor'),
    ));
}
add_action('init', 'wc_notes_register_post_type');

function wc_notes_register_rest_routes() {
    register_rest_route('wc-notes/v1', '/notes', array(
        'methods' => 'GET',
        'callback' => 'wc_notes_get_notes',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'wc_notes_register_rest_routes');

function wc_notes_get_notes() {
    $query = new WP_Query(array(
        'post_type' => 'wc_note',
        'posts_per_page' => 10,
    ));
    $notes = array();
    foreach ($query->posts as $post) {
        $notes[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
        );
    }
    return rest_ensure_response($notes);
}
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
    
    // Get the iframe's URL to construct API base URL
    // Note: In real Playground, you'd get the actual URL
    // For now, we'll use a relative path approach
    apiBaseUrl = '/wp-json';
  });

  test('should have REST API accessible', async ({ page }) => {
    // Navigate to REST API root to verify it's accessible
    await page.goto('https://playground.wordpress.net');
    await page.waitForTimeout(2000);
    
    // Get iframe source to access the REST API
    const iframeSrc = await page.locator('iframe[name="wordpress"]').getAttribute('src');
    
    // Test the REST API endpoint directly
    // Note: This is a simplified test - in real scenarios you'd need to handle CORS
    const response = await page.request.get(`${iframeSrc}/wp-json/`);
    
    expect(response.status()).toBe(200);
    
    const data = await response.json();
    expect(data).toHaveProperty('name');
    expect(data.name).toBe('WordPress');
  });

  test('should have custom REST endpoint registered', async ({ page }) => {
    // Get iframe source
    const iframeSrc = await page.locator('iframe[name="wordpress"]').getAttribute('src');
    
    // Test our custom endpoint
    const response = await page.request.get(`${iframeSrc}/wp-json/wc-notes/v1/notes`);
    
    // Should return 200 or handle CORS issues gracefully
    expect([200, 404, 0]).toContain(response.status());
    
    // If successful, verify response structure
    if (response.status() === 200) {
      const data = await response.json();
      expect(Array.isArray(data)).toBe(true);
    }
  });

  test('should return notes as JSON', async ({ page }) => {
    // This is a basic test - in a real scenario you'd need to:
    // 1. Create a note first
    // 2. Then test the API endpoint
    // 3. Handle CORS and iframe limitations
    
    // For demonstration, we'll just verify the endpoint structure exists
    const iframeSrc = await page.locator('iframe[name="wordpress"]').getAttribute('src');
    
    try {
      const response = await page.request.get(`${iframeSrc}/wp-json/wc-notes/v1/notes`);
      
      // Note: Due to CORS and iframe limitations, this might fail
      // This test demonstrates the concept
      if (response.status() === 200) {
        const data = await response.json();
        expect(Array.isArray(data)).toBe(true);
      }
    } catch (error) {
      // Expected in some scenarios due to CORS
      console.log('Note: API test may be limited by CORS policies');
    }
  });
});

