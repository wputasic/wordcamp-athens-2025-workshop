const { test, expect } = require('@playwright/test');
const { loadBlueprint, waitForWordPressReady, navigateInFrame } = require('./utils/playground-helpers');

/**
 * Simple test to verify WordPress admin loads
 * 
 * This is a minimal test that:
 * - Loads WordPress Playground
 * - Verifies the admin area is accessible
 */
test.describe('WordPress Admin - Basic Load Test', () => {
  let iframe;

  test('should load WordPress admin successfully', async ({ page }) => {
    // Minimal blueprint - just login
    const blueprint = {
      "$schema": "https://playground.wordpress.net/blueprint-schema.json",
      "landingPage": "/wp-admin/",
      "preferredVersions": {
        "php": "8.2",
        "wp": "6.9"
      },
      "steps": [
        {
          "step": "login",
          "username": "admin"
        }
      ]
    };

    // Load blueprint into Playground
    iframe = await loadBlueprint(page, blueprint);
    
    // Wait for WordPress to be ready
    await waitForWordPressReady(iframe);
    
    // Explicitly navigate to admin area (blueprint landingPage may not work)
    await navigateInFrame(iframe, '/wp-admin/');
    
    // Wait for admin page to load
    await waitForWordPressReady(iframe, true);
    
    // Verify we're in the admin area
    const body = iframe.locator('body');
    await expect(body).toHaveClass(/wp-admin/);
    
    // Verify admin bar is visible
    const adminBar = iframe.locator('#wpadminbar');
    await expect(adminBar).toBeVisible();
    
    // Verify dashboard is loaded (check for common dashboard elements)
    const dashboard = iframe.locator('.wp-dashboard, #dashboard-widgets, h1');
    await expect(dashboard.first()).toBeVisible();
  });
});

