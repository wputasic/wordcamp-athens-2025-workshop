# Project Structure - WordCamp Athens 2025 Workshop

## Complete Repository Structure

```
wordcamp-athens-2025-workshop/
│
├── README.md                    # Main workshop documentation
├── LICENSE                      # GPL-2.0 license
├── .gitignore                   # Git ignore rules
├── PRESENTATION.md              # Presentation guide with titles and descriptions
├── PROJECT-STRUCTURE.md        # This file
│
├── 01-basic-plugin/
│   ├── README.md                # Example documentation
│   ├── plugin.php               # Basic plugin with CPT
│   └── blueprint.json           # WordPress Playground blueprint
│
├── 02-translations/
│   ├── README.md                # Translation documentation
│   ├── plugin.php               # Plugin with i18n support
│   ├── blueprint.json           # Blueprint with Greek locale
│   └── languages/
│       └── wc-notes-el_GR.po    # Greek translation file
│
├── 03-dashboard-widget/
│   ├── README.md                # Dashboard widget documentation
│   ├── plugin.php               # Plugin with dashboard widget
│   └── blueprint.json           # WordPress Playground blueprint
│
├── 04-rest-api/
│   ├── README.md                # REST API documentation
│   ├── plugin.php               # Full CRUD REST API implementation
│   ├── blueprint.json           # WordPress Playground blueprint
│   └── api-examples.md          # API usage examples and curl commands
│
├── 05-rest-api-cpt/
│   ├── README.md                # CPT + REST API documentation
│   ├── plugin.php               # CPT with REST API support
│   ├── meta-fields.php          # Custom meta fields registration
│   └── blueprint.json           # WordPress Playground blueprint
│
└── 06-admin-ui-rest/
    ├── README.md                # Admin UI documentation
    ├── plugin.php               # Main plugin file
    ├── blueprint.json           # WordPress Playground blueprint
    ├── admin/
    │   ├── admin-menu.php       # Menu registration
    │   └── admin-page.php       # Admin page template
    └── assets/
        ├── admin.js             # JavaScript for REST API calls
        └── admin.css            # Admin page styles
```

## File Count Summary

- **Total Directories**: 10
- **PHP Files**: 12
- **JSON Files** (Blueprints): 6
- **Markdown Files**: 9
- **JavaScript Files**: 1
- **CSS Files**: 1
- **Translation Files**: 1 (.po)
- **Configuration Files**: 3 (.gitignore, LICENSE, etc.)

## Example Progression

1. **Basic Plugin** - Learn plugin structure and Custom Post Types
2. **Translations** - Add internationalization support
3. **Dashboard Widget** - Enhance admin experience
4. **REST API** - Build full CRUD endpoints
5. **REST API + CPT** - Integrate with Custom Post Types
6. **Admin UI + REST** - Create modern JavaScript interface

## Ready for Production

✅ All examples are complete and functional
✅ Blueprints are ready to use in WordPress Playground
✅ Documentation is comprehensive
✅ Code follows WordPress coding standards
✅ Presentation guide included
✅ Git repository structure ready

## Next Steps

1. Initialize Git repository: `git init`
2. Add all files: `git add .`
3. Create initial commit: `git commit -m "Initial commit: WordCamp Athens 2025 Workshop"`
4. Push to GitHub: `git remote add origin <your-repo-url> && git push -u origin main`
5. Share with attendees!

---

**Project Status**: ✅ Complete and Ready for WordCamp Athens 2025

