/**
 * Helper functions for WordPress Playground testing
 */

/**
 * Navigate within a frame using evaluate (FrameLocator doesn't have goto)
 * @param {import('@playwright/test').FrameLocator} iframe - WordPress iframe
 * @param {string} url - URL to navigate to
 */
async function navigateInFrame(iframe, url) {
  // Use a locator's evaluate method to execute code in the frame context
  // Note: first parameter is the element, second is our URL argument
  await iframe.locator('body').evaluate((element, url) => {
    window.location.href = url;
  }, url);
  // Wait for navigation to complete
  await iframe.locator('body').waitFor({ timeout: 10000 });
}

/**
 * Wait for WordPress to be fully loaded in Playground
 * @param {import('@playwright/test').FrameLocator} iframe - WordPress iframe
 * @param {boolean} requireAdmin - If true, wait for admin area specifically
 */
async function waitForWordPressReady(iframe, requireAdmin = false) {
  // Wait for admin bar to be visible (indicates WordPress is loaded and user is logged in)
  await iframe.locator('#wpadminbar').waitFor({ timeout: 30000 });
  
  // If admin area is required, wait for it or navigate to it
  if (requireAdmin) {
    // Check if we're already in admin, if not navigate there
    const isAdmin = await iframe.locator('body.wp-admin').count() > 0;
    if (!isAdmin) {
      await navigateInFrame(iframe, '/wp-admin/');
    }
    // Wait for admin body class
    await iframe.locator('body.wp-admin').waitFor({ timeout: 30000 });
  }
}

/**
 * Load a blueprint into WordPress Playground
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {Object} blueprint - Blueprint JSON object
 */
async function loadBlueprint(page, blueprint) {
  // Navigate directly to blueprint URL if provided, otherwise base URL
  const url = blueprint
    ? `https://playground.wordpress.net/?blueprint=${encodeURIComponent(JSON.stringify(blueprint))}`
    : 'https://playground.wordpress.net';
  
  await page.goto(url);
  
  // Wait for page to be in a ready state
  await page.waitForLoadState('networkidle');
  
  // Wait for iframe to be present (use more generic selector)
  await page.waitForSelector('iframe', { timeout: 30000 });
  
  // WordPress Playground uses nested iframes - get the inner iframe where WordPress actually runs
  const outerIframe = page.frameLocator('iframe').first();
  
  // Wait for nested iframe to be present
  // We'll wait for WordPress content to appear, which confirms the nested iframe is loaded
  const iframeLocator = outerIframe.frameLocator('iframe').first();
  
  // Wait for WordPress to be ready inside the iframe
  // Check if blueprint specifies admin landing page
  const requiresAdmin = blueprint?.landingPage?.includes('/wp-admin');
  await waitForWordPressReady(iframeLocator, requiresAdmin);
  
  return iframeLocator;
}

/**
 * Login to WordPress admin
 * @param {import('@playwright/test').FrameLocator} iframe - WordPress iframe
 * @param {string} username - Admin username (default: admin)
 * @param {string} password - Admin password (default: password)
 */
async function loginToWordPress(iframe, username = 'admin', password = 'password') {
  // Navigate to login page if not already there
  await iframe.locator('#user_login').fill(username);
  await iframe.locator('#user_pass').fill(password);
  await iframe.locator('#wp-submit').click();
  
  // Wait for dashboard to load
  await iframe.waitForSelector('.wp-dashboard', { timeout: 10000 });
}

/**
 * Navigate to a specific admin page
 * @param {import('@playwright/test').FrameLocator} iframe - WordPress iframe
 * @param {string} adminPage - Admin page URL (e.g., 'edit.php?post_type=wc_note')
 */
async function navigateToAdminPage(iframe, adminPage) {
  await navigateInFrame(iframe, `/wp-admin/${adminPage}`);
  await waitForWordPressReady(iframe, true);
}

/**
 * Check if a plugin is active
 * @param {import('@playwright/test').FrameLocator} iframe - WordPress iframe
 * @param {string} pluginName - Plugin name or slug
 * @returns {Promise<boolean>} - True if plugin is active
 */
async function isPluginActive(iframe, pluginName) {
  await navigateToAdminPage(iframe, 'plugins.php');
  
  // Check if plugin appears in the active plugins list
  const pluginRow = iframe.locator(`tr[data-plugin*="${pluginName}"]`);
  const isActive = await pluginRow.locator('.activate').count() === 0;
  
  return isActive;
}

module.exports = {
  waitForWordPressReady,
  loadBlueprint,
  loginToWordPress,
  navigateToAdminPage,
  navigateInFrame,
  isPluginActive,
};

