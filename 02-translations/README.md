# 02. Translations - WordCamp Notes (Greek)

## 📋 Overview

**Presentation Title**: Internationalizing Your WordPress Plugin  
**Presentation Description**: Make your plugin accessible to Greek-speaking attendees by implementing WordPress translations with .po/.mo files.

This example demonstrates how to add Greek translations to the WordCamp Notes plugin. You'll learn WordPress internationalization (i18n) best practices and how to create translation files.

## 🎯 What You'll Learn

- WordPress translation functions (`__()`, `_e()`, `_x()`, `_n()`)
- Loading translation files with `load_plugin_textdomain()`
- Creating .po and .mo files
- Using WordPress Playground with different locales
- Translation workflow for plugins

## 🚀 Quick Start

1. Copy the contents of `blueprint.json`
2. Go to [WordPress Playground](https://playground.wordpress.net)
3. Click "Import Blueprint" and paste the JSON
4. The plugin will install with Greek locale enabled

## 📁 Files

- `plugin.php` - Plugin with translation functions
- `blueprint.json` - Blueprint with Greek locale
- `languages/wc-notes-el_GR.po` - Greek translation source file
- `languages/wc-notes-el_GR.mo` - Compiled translation file

## 🌍 Translation Examples

- "WordCamp Notes" → "Σημειώσεις WordCamp"
- "Add New Note" → "Προσθήκη Νέας Σημειώσης"
- "Session Title" → "Τίτλος Συνεδρίας"
- "Take Notes" → "Κάνε Σημειώσεις"

## 🔧 Key Functions

- `__()` - Translate and return string
- `_e()` - Translate and echo string
- `load_plugin_textdomain()` - Load translation files
- `_x()` - Translate with context
- `_n()` - Translate plural strings

## ✅ Expected Outcome

After running the blueprint:
- WordPress admin interface switches to Greek
- All plugin strings are translated
- Plugin menus and labels show in Greek
- Custom post type labels are translated

## 🎓 Next Steps

- Add more language translations (Spanish, Italian, German)
- Create translation files for admin interface
- Implement RTL (Right-to-Left) language support
- Use translation tools like Poedit or WPML

## 🔗 Related Examples

- [01. Basic Plugin](./../01-basic-plugin/README.md) - Build the base plugin
- [03. Dashboard Widget](./../03-dashboard-widget/README.md) - Add translatable dashboard widget

