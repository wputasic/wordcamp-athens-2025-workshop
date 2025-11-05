# 04. REST API - Full CRUD Operations

## 📋 Overview

**Presentation Title**: Building Powerful REST APIs for WordPress  
**Presentation Description**: Learn how to create complete REST API endpoints with authentication, validation, and error handling for managing your plugin data.

This example demonstrates how to create a full CRUD (Create, Read, Update, Delete) REST API for the WordCamp Notes plugin. You'll learn authentication, validation, error handling, and best practices.

## 🎯 What You'll Learn

- Registering REST API routes
- Implementing GET, POST, PUT, DELETE endpoints
- Authentication and authorization
- Input validation and sanitization
- Error handling and responses
- Pagination and filtering
- Search functionality

## 🚀 Quick Start

1. Copy the contents of `blueprint.json`
2. Go to [WordPress Playground](https://playground.wordpress.net)
3. Click "Import Blueprint" and paste the JSON
4. Test endpoints using the API documentation or curl

## 📁 Files

- `plugin.php` - Full CRUD REST API implementation
- `blueprint.json` - WordPress Playground configuration
- `api-examples.md` - API usage examples and curl commands

## 🔌 Available Endpoints

### GET /wp-json/wc-notes/v1/notes
List all notes with pagination, filtering, and search.

**Query Parameters**:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 10, max: 100)
- `search` - Search in title and content
- `session` - Filter by session title
- `date` - Filter by date (YYYY-MM-DD)

### GET /wp-json/wc-notes/v1/notes/{id}
Get a single note by ID.

### POST /wp-json/wc-notes/v1/notes
Create a new note (requires authentication).

**Request Body**:
```json
{
  "title": "My Session Notes",
  "content": "Important points from the session...",
  "session_title": "WordPress REST API",
  "speaker": "Jane Smith",
  "session_time": "14:00"
}
```

### PUT /wp-json/wc-notes/v1/notes/{id}
Update an existing note (requires authentication).

### DELETE /wp-json/wc-notes/v1/notes/{id}
Delete a note (requires authentication).

## 🔐 Authentication

The API uses WordPress built-in authentication:
- Application Passwords (recommended)
- Cookie authentication for logged-in users
- Nonce verification for admin requests

## ✅ Expected Outcome

After running the blueprint:
- All REST endpoints are registered and working
- Can retrieve notes via GET requests
- Can create, update, and delete notes with authentication
- Pagination and filtering work correctly
- Error handling provides meaningful messages

## 🎓 Next Steps

- Add authentication with Application Passwords
- Implement rate limiting
- Add webhook support for external integrations
- Create API documentation with Swagger/OpenAPI
- Add caching for better performance

## 🔗 Related Examples

- [03. Dashboard Widget](./../03-dashboard-widget/README.md) - Use REST API to fetch widget data
- [05. REST API + CPT](./../05-rest-api-cpt/README.md) - Combine with Custom Post Types
- [06. Admin UI + REST](./../06-admin-ui-rest/README.md) - Build admin interface with REST API

