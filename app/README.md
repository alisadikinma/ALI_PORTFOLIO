# App Directory

Core Laravel application logic following MVC architecture patterns.

## Structure

```
app/
├── Http/
│   ├── Controllers/     # Request handling and business logic
│   └── Middleware/      # HTTP middleware components
├── Models/             # Eloquent models and database interactions
└── Services/           # Business logic services
```

## Controllers (`Http/Controllers/`)

### Main Controllers
- `HomeWebController.php` - Homepage management with dynamic sections
- `ProjectController.php` - Portfolio projects CRUD operations
- `SettingController.php` - Site-wide configuration management
- `ContactController.php` - Contact form handling
- `GaleriController.php` - Gallery/media management
- `AwardController.php` - Awards and achievements
- `TestimonialController.php` - Client testimonials
- `BeritaController.php` - News/blog content management

## Models

### Core Models
- `Project.php` - Portfolio projects with image handling and auto-slug generation
- `Setting.php` - Global site configuration (company info, social links, statistics)
- `LookupData.php` - Flexible lookup system for homepage sections and categories

### Key Features
- Custom primary keys (`id_project`, `id_setting`)
- Automatic image cleanup on deletion
- Dynamic homepage section management
- JSON metadata support for extensibility

## Services

- `HomepageSectionService.php` - Homepage section data management
- `SeoService.php` - SEO functionality

## Middleware

- Standard Laravel middleware
- `DisableScreenCapture.php` - Custom security middleware

## Architecture Patterns

- **Repository Pattern**: Through Eloquent models
- **Service Layer**: Business logic separated from controllers
- **Custom Primary Keys**: Non-standard ID naming convention
- **Image Management**: Automatic file handling and cleanup
- **Scopes**: Query optimization for active/ordered content
