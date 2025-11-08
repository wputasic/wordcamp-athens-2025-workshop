# 07. Playwright Tests - WordPress Playground

## 📋 Overview

**Presentation Title**: Automated Testing with WordPress Playground  
**Presentation Description**: Learn how to write and run automated tests for WordPress plugins using Playwright and WordPress Playground. Test your plugins without local setup.

This example demonstrates how to write basic automated tests for WordPress plugins using Playwright, testing plugins directly within WordPress Playground.

## 🎯 What You'll Learn

- Setting up Playwright for WordPress testing
- Writing basic end-to-end tests
- Testing WordPress plugins in Playground
- Testing admin interfaces
- Testing REST API endpoints
- Automating plugin validation

## 🚀 Quick Start

### Understanding the Options

**Option 1: VS Code Extension** (For Development)
- Use the WordPress Playground VS Code extension for manual development and testing
- Great for writing code and manually testing plugins
- Not needed for running automated Playwright tests

**Option 2: Web Playground** (For Manual Testing)
- Use https://playground.wordpress.net in your browser
- Perfect for manual testing and quick experiments
- Can import blueprints directly

**Option 3: Playwright Tests** (For Automated Testing) ⭐ **This Example**
- Automated tests that run against the web Playground
- No VS Code extension needed
- Tests run in headless browsers automatically
- Perfect for CI/CD and regression testing

### Prerequisites

Before running the Playwright tests, ensure you have:

- **Node.js** (v16 or higher) - [Download Node.js](https://nodejs.org/)
- **npm** (comes with Node.js) or **yarn**

Check if you have Node.js installed:
```bash
node --version  # Should show v16 or higher
npm --version   # Should show version number
```

### Step-by-Step Installation

#### Step 1: Navigate to the Test Directory

Open your terminal and navigate to the Playwright tests directory:

```bash
cd /Users/utasic/Desktop/wordcamp-athens-2025-workshop/07-playwright-tests
```

Or if you're already in the workshop directory:
```bash
cd 07-playwright-tests
```

#### Step 2: Install Node.js Dependencies

Install the required packages (Playwright and testing utilities):

```bash
npm install
```

This will:
- Install `@playwright/test` package
- Create `node_modules/` directory
- Create `package-lock.json` file

**Expected output**: You should see "added X packages" message.

#### Step 3: Install Playwright Browsers

Playwright needs to download browser binaries (Chromium, Firefox, WebKit):

```bash
npx playwright install
```

This will:
- Download Chromium browser (~170MB)
- Download Firefox browser (~85MB)
- Download WebKit browser (~170MB)
- Take a few minutes depending on your internet speed

**Expected output**: "Installing browsers..." and progress bars.

**Note**: If you only want to install Chromium (smaller download):
```bash
npx playwright install chromium
```

#### Step 4: Verify Installation

Verify everything is set up correctly:

```bash
npx playwright --version
```

**Expected output**: Should show Playwright version (e.g., "Version 1.40.0")

### Running Tests

#### Option A: Run All Tests (Recommended First Time)

Run all test suites at once:

```bash
npm test
```

**What happens**:
1. Playwright opens browser(s) in headless mode
2. Tests load WordPress Playground
3. Tests execute automatically
4. Results are displayed in terminal
5. HTML report is generated in `playwright-report/`

**Expected output**:
```
Running 6 tests using 1 worker

  ✓ basic-plugin.spec.js:15:5 › WordPress Playground loads successfully
  ✓ basic-plugin.spec.js:21:5 › Plugin installs and activates
  ...
```

#### Option B: Run Tests with UI (Easiest for Beginners)

Run tests in interactive mode to see what's happening:

```bash
npm run test:ui
```

**What happens**:
- Opens Playwright UI in your browser
- Shows tests in a visual interface
- You can click to run individual tests
- See step-by-step execution
- View screenshots and videos

**Best for**: Learning how tests work, debugging failures

#### Option C: Run Tests in Headed Mode (See Browser)

Run tests with visible browser windows:

```bash
npm run test:headed
```

**What happens**:
- Browser windows open and you can see them
- Tests execute visibly
- Useful for debugging

**Best for**: Seeing what the tests are doing visually

#### Option D: Run Specific Test Suite

Run only one type of test:

```bash
npm run test:basic    # Only plugin installation tests
npm run test:admin    # Only admin interface tests
npm run test:api      # Only REST API tests
```

#### Option E: Debug Tests

Run tests in debug mode:

```bash
npm run test:debug
```

**What happens**:
- Opens Playwright Inspector
- Test execution pauses at breakpoints
- Step through tests line by line
- Inspect page state

**Best for**: Debugging test failures

### Viewing Test Results

After running tests, you'll get:

1. **Terminal Output**: Summary of passed/failed tests
2. **HTML Report**: Detailed report with screenshots

To open the HTML report:
```bash
npx playwright show-report
```

Or manually open:
```
playwright-report/index.html
```

### Troubleshooting

**Problem**: `npm: command not found`
- **Solution**: Install Node.js from https://nodejs.org/

**Problem**: Tests fail with "timeout" errors
- **Solution**: Playground might be slow. Increase timeout in `playwright.config.js`

**Problem**: Browser doesn't download
- **Solution**: Check internet connection, run `npx playwright install` again

**Problem**: Tests can't find WordPress
- **Solution**: Make sure you have internet connection (Playground needs to load)

**Problem**: CORS errors in REST API tests
- **Solution**: This is expected - REST API tests have limitations due to iframe CORS policies

## 📁 Files

- `package.json` - Project dependencies and scripts
- `playwright.config.js` - Playwright configuration
- `tests/basic-plugin.spec.js` - Plugin installation tests
- `tests/admin-interface.spec.js` - Admin UI interaction tests
- `tests/rest-api.spec.js` - REST API endpoint tests
- `tests/utils/playground-helpers.js` - Helper functions for Playground

## 🧪 Test Suites

### 1. Basic Plugin Tests (`basic-plugin.spec.js`)

Tests plugin installation and basic functionality:

- ✅ WordPress Playground loads successfully
- ✅ Plugin installs and activates
- ✅ Plugin menu appears in admin
- ✅ Custom Post Type is registered

**Example Test**:
```javascript
test('should install and activate WordCamp Notes plugin', async () => {
  const pluginActive = await isPluginActive(iframe, 'wc-notes');
  expect(pluginActive).toBe(true);
});
```

### 2. Admin Interface Tests (`admin-interface.spec.js`)

Tests admin interface interactions:

- ✅ Navigate to plugin pages
- ✅ Create new notes
- ✅ Verify notes appear in list
- ✅ Test CRUD operations

**Example Test**:
```javascript
test('should be able to create a new note', async () => {
  await navigateToAdminPage(iframe, 'post-new.php?post_type=wc_note');
  await iframe.locator('#title').fill('Test Note');
  await iframe.locator('#publish').click();
  // Verify note was created
});
```

### 3. REST API Tests (`rest-api.spec.js`)

Tests REST API endpoints:

- ✅ REST API is accessible
- ✅ Custom endpoints are registered
- ✅ API returns correct data format

**Note**: REST API tests may have CORS limitations when testing through iframes.

## 🔧 Helper Functions

The `playground-helpers.js` file provides utilities:

- `loadBlueprint()` - Load a blueprint into Playground
- `loginToWordPress()` - Login to WordPress admin
- `waitForWordPressReady()` - Wait for WordPress to load
- `navigateToAdminPage()` - Navigate to admin pages
- `isPluginActive()` - Check if plugin is active

## 📝 Writing Your Own Tests

### Basic Test Structure

```javascript
const { test, expect } = require('@playwright/test');
const { loadBlueprint, loginToWordPress } = require('./utils/playground-helpers');

test.describe('My Plugin Tests', () => {
  let iframe;

  test.beforeEach(async ({ page }) => {
    // Load blueprint
    const blueprint = { /* your blueprint */ };
    iframe = await loadBlueprint(page, blueprint);
    await loginToWordPress(iframe);
  });

  test('should do something', async () => {
    // Your test code
    await iframe.goto('/wp-admin/');
    // Assertions
    await expect(iframe.locator('h1')).toBeVisible();
  });
});
```

### Working with Playground Iframe

Since WordPress runs in an iframe, use `iframe.locator()` instead of `page.locator()`:

```javascript
// ✅ Correct
await iframe.locator('#title').fill('Test');

// ❌ Wrong
await page.locator('#title').fill('Test');
```

## ✅ Expected Outcomes

After running the tests:

- All basic plugin tests pass
- Admin interface tests verify functionality
- REST API tests check endpoint availability
- Test reports generated in `playwright-report/`

## 🎓 Next Steps

- Add more comprehensive test coverage
- Test different WordPress versions
- Add visual regression tests
- Integrate with CI/CD pipelines
- Test plugin updates and migrations
- Add performance tests

## 🔗 Related Examples

- [01. Basic Plugin](./../01-basic-plugin/README.md) - Plugin being tested
- [04. REST API](./../04-rest-api/README.md) - REST API endpoints
- [06. Admin UI + REST](./../06-admin-ui-rest/README.md) - Admin interface

## 📚 Resources

- [Playwright Documentation](https://playwright.dev/)
- [WordPress Playground](https://playground.wordpress.net)
- [Playwright Best Practices](https://playwright.dev/docs/best-practices)

## ⚠️ Notes

- Tests run against WordPress Playground (browser-based)
- Some tests may have CORS limitations
- Playwright handles iframe interactions automatically
- Tests are designed to be basic examples - expand as needed

---

**Tip**: Start with `npm run test:ui` to see tests run interactively and understand the flow!

