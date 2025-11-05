# 05. REST API + Custom Post Type

## 📋 Overview

**Presentation Title**: Integrating Custom Post Types with REST API  
**Presentation Description**: Combine the power of Custom Post Types with REST API to expose meta fields and taxonomies, creating a complete data management system.

This example demonstrates how to enable REST API for Custom Post Types and expose custom meta fields and taxonomies via the REST API. You'll learn how to customize REST endpoints and work with structured data.

## 🎯 What You'll Learn

- Enabling REST API for Custom Post Types
- Exposing custom meta fields via REST API
- Registering taxonomies with REST API support
- Customizing REST endpoints
- Native WordPress REST API integration
- Working with meta fields and taxonomies

## 🚀 Quick Start

1. Copy the contents of `blueprint.json`
2. Go to [WordPress Playground](https://playground.wordpress.net)
3. Click "Import Blueprint" and paste the JSON
4. Access notes via native WordPress REST endpoints

## 📁 Files

- `plugin.php` - CPT with REST API support
- `meta-fields.php` - Custom meta fields registration
- `blueprint.json` - WordPress Playground configuration

## 🔌 Available Endpoints

### Native WordPress REST Endpoints

- `GET /wp-json/wp/v2/wc-note` - List all notes
- `GET /wp-json/wp/v2/wc-note/{id}` - Get single note
- `POST /wp-json/wp/v2/wc-note` - Create note
- `PUT /wp-json/wp/v2/wc-note/{id}` - Update note
- `DELETE /wp-json/wp/v2/wc-note/{id}` - Delete note

### Custom Endpoints

- `GET /wp-json/wc-notes/v1/stats` - Get statistics

## 📊 Features

- **Custom Post Type** - `wc_note` with REST API enabled
- **Custom Taxonomies** - Session tracks and skill levels
- **Meta Fields** - Speaker name, session time, room number
- **REST API Support** - All fields exposed via REST
- **Statistics Endpoint** - Custom endpoint for plugin stats

## ✅ Expected Outcome

After running the blueprint:
- Custom Post Type accessible via REST API
- Meta fields visible in REST responses
- Taxonomies available via REST endpoints
- Custom statistics endpoint working
- Native WordPress REST API integration

## 🎓 Next Steps

- Create custom REST endpoints for statistics
- Add filtering and sorting capabilities
- Implement custom field validation
- Add custom REST controllers
- Create REST API documentation

## 🔗 Related Examples

- [04. REST API](./../04-rest-api/README.md) - Custom REST endpoints
- [06. Admin UI + REST](./../06-admin-ui-rest/README.md) - Build admin interface

