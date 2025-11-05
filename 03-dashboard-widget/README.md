# 03. Dashboard Widget - WordCamp Notes Summary

## 📋 Overview

**Presentation Title**: Enhancing the WordPress Admin Experience  
**Presentation Description**: Create custom dashboard widgets that provide valuable insights and quick actions for your plugin users.

This example demonstrates how to add a custom dashboard widget to the WordCamp Notes plugin. The widget shows statistics about notes and provides quick access to common actions.

## 🎯 What You'll Learn

- Creating custom dashboard widgets with `wp_add_dashboard_widget()`
- Displaying dynamic data in dashboard widgets
- Integrating widgets with plugin data
- Making widgets translatable
- WordPress dashboard API

## 🚀 Quick Start

1. Copy the contents of `blueprint.json`
2. Go to [WordPress Playground](https://playground.wordpress.net)
3. Click "Import Blueprint" and paste the JSON
4. The widget will appear on the WordPress dashboard

## 📁 Files

- `plugin.php` - Plugin with dashboard widget implementation
- `blueprint.json` - WordPress Playground configuration

## 🎨 Widget Features

- **Total Notes Count** - Shows total number of notes
- **Today's Notes** - Count of notes created today
- **Recent Notes** - List of 3 most recent notes
- **Quick Actions** - Button to add new note
- **Translatable** - All text uses translation functions

## 🔧 Key Functions

- `wp_add_dashboard_widget()` - Register dashboard widget
- `wp_count_posts()` - Get post counts
- `get_posts()` - Retrieve recent posts
- Translation functions for i18n support

## ✅ Expected Outcome

After running the blueprint:
- A new "WordCamp Notes Summary" widget appears on the dashboard
- Widget shows statistics about notes
- Quick action button to add new note
- All text is translatable (ready for Greek)

## 🎓 Next Steps

- Add AJAX refresh functionality
- Create multiple widget instances
- Integrate with WordPress statistics
- Add charts or graphs using Chart.js
- Make widget configurable

## 🔗 Related Examples

- [02. Translations](./../02-translations/README.md) - Add Greek translations to widget
- [04. REST API](./../04-rest-api/README.md) - Fetch widget data via REST API

