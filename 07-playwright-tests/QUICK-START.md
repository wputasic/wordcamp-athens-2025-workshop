# Quick Start Guide - Playwright Tests

## 🎯 TL;DR - Run Tests in 3 Steps

```bash
# Step 1: Install dependencies
npm install

# Step 2: Install browsers
npx playwright install chromium

# Step 3: Run tests
npm test
```

## 📋 Full Setup (One-Time)

### 1. Check Prerequisites
```bash
node --version   # Need v16+
npm --version    # Should work
```

### 2. Install Everything
```bash
cd 07-playwright-tests
npm install
npx playwright install chromium
```

### 3. Verify Setup
```bash
npx playwright --version
```

## 🚀 Running Tests

### Quick Commands

| Command | What It Does |
|---------|-------------|
| `npm test` | Run all tests (headless) |
| `npm run test:ui` | Run with visual UI (best for beginners) |
| `npm run test:headed` | Run with visible browser |
| `npm run test:basic` | Run only plugin tests |
| `npm run test:admin` | Run only admin tests |
| `npm run test:api` | Run only API tests |
| `npm run test:debug` | Debug mode with inspector |

## 🔍 Understanding the Options

### Do I Need VS Code Extension?
**No!** The VS Code WordPress Playground extension is for:
- Manual development in VS Code
- Writing code with autocomplete
- Manual testing

**Not needed for** Playwright automated tests.

### Do Tests Run on Web Playground?
**Yes!** Tests automatically:
- Open https://playground.wordpress.net
- Load your blueprints
- Run tests in the browser
- No manual interaction needed

## 📊 What Gets Tested?

1. **Plugin Installation** - Does plugin install and activate?
2. **Admin Interface** - Can you create/edit notes?
3. **REST API** - Are endpoints accessible?

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| `npm: command not found` | Install Node.js from nodejs.org |
| Tests timeout | Check internet, Playground might be slow |
| Browser not found | Run `npx playwright install` |
| CORS errors | Expected for API tests (iframe limitation) |

## 📖 Next Steps

1. **First time?** → Run `npm run test:ui` to see tests visually
2. **Want to see browsers?** → Run `npm run test:headed`
3. **Something failing?** → Run `npm run test:debug` to step through
4. **View results** → Run `npx playwright show-report`

## 💡 Pro Tips

- Start with `test:ui` to understand what's happening
- Use `test:headed` to see the browser in action
- Check `playwright-report/` for detailed results
- All tests run against live Playground (needs internet)

---

**Ready?** → `cd 07-playwright-tests && npm install && npx playwright install chromium && npm test`

