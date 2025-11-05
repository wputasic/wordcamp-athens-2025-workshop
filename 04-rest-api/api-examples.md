# REST API Usage Examples

## Testing with cURL

### Get All Notes
```bash
curl -X GET "https://yoursite.com/wp-json/wc-notes/v1/notes"
```

### Get Notes with Pagination
```bash
curl -X GET "https://yoursite.com/wp-json/wc-notes/v1/notes?page=1&per_page=10"
```

### Search Notes
```bash
curl -X GET "https://yoursite.com/wp-json/wc-notes/v1/notes?search=wordpress"
```

### Get Single Note
```bash
curl -X GET "https://yoursite.com/wp-json/wc-notes/v1/notes/1"
```

### Create Note (with Authentication)
```bash
curl -X POST "https://yoursite.com/wp-json/wc-notes/v1/notes" \
  -H "Content-Type: application/json" \
  -H "Authorization: Basic base64(username:password)" \
  -d '{
    "title": "My Session Notes",
    "content": "Important points from the session...",
    "session_title": "WordPress REST API",
    "speaker": "Jane Smith",
    "session_time": "14:00"
  }'
```

### Update Note
```bash
curl -X PUT "https://yoursite.com/wp-json/wc-notes/v1/notes/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Basic base64(username:password)" \
  -d '{
    "title": "Updated Title",
    "content": "Updated content..."
  }'
```

### Delete Note
```bash
curl -X DELETE "https://yoursite.com/wp-json/wc-notes/v1/notes/1" \
  -H "Authorization: Basic base64(username:password)"
```

## JavaScript Examples

### Fetch All Notes
```javascript
fetch('/wp-json/wc-notes/v1/notes')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Create Note
```javascript
fetch('/wp-json/wc-notes/v1/notes', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({
    title: 'My Note',
    content: 'Note content',
    session_title: 'Session Name'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

## Response Format

### Success Response
```json
{
  "id": 1,
  "title": "WordPress Playground Workshop",
  "content": "Great session on...",
  "session_title": "Mastering WordPress Playground",
  "speaker": "John Doe",
  "date": "2024-06-15T10:00:00",
  "link": "https://example.com/wp-json/wc-notes/v1/notes/1"
}
```

### Error Response
```json
{
  "code": "rest_cannot_create",
  "message": "Sorry, you are not allowed to create notes.",
  "data": {
    "status": 403
  }
}
```

