# 01. Basic Plugin - WordCamp Notes

## 📋 Overview

**Presentation Title**: Building Your First WordPress Plugin  
**Presentation Description**: Learn the fundamentals by creating a practical WordCamp Notes plugin that attendees can use during the conference.

This example demonstrates how to create a basic WordPress plugin from scratch using WordPress Playground. You'll build a WordCamp Notes plugin that allows users to take and organize notes during sessions.

## 🎯 What You'll Learn

- WordPress plugin structure and organization
- Custom Post Type registration
- WordPress Playground blueprints
- Plugin activation and basic functionality

## 🚀 Quick Start

1. Copy the contents of `blueprint.json`
2. Go to [WordPress Playground](https://playground.wordpress.net)
3. Click "Import Blueprint" and paste the JSON
4. The plugin will be automatically installed and activated

## 📁 Files

- `plugin.php` - Main plugin file with Custom Post Type registration
- `blueprint.json` - WordPress Playground configuration

## 🔧 Code Structure

The plugin registers a custom post type called `wc_note` for storing WordCamp session notes. It includes:
- Session tracking (title, speaker, time)
- Note content management
- Minimal dependencies

## ✅ Expected Outcome

After running the blueprint, you should see:
- A new "WordCamp Notes" menu item in the WordPress admin
- Ability to create, edit, and manage notes
- Custom post type working in the admin area

## 🎓 Next Steps

- Add custom meta boxes for session details
- Implement note categories
- Add custom fields for speaker and session time

## 🔗 Related Examples

- [02. Translations](./../02-translations/README.md) - Add Greek translations
- [03. Dashboard Widget](./../03-dashboard-widget/README.md) - Add dashboard widget

