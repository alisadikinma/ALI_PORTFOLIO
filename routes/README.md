# Routes Directory

URL routing configuration for the Laravel application.

## Structure

```
routes/
├── web.php        # Web application routes
├── api.php        # API endpoints (if any)
└── console.php    # Artisan console commands
```

## Web Routes (`web.php`)

Main application routing with these patterns:

### Public Routes
- Homepage with dynamic sections
- Portfolio/project listings and details
- About, gallery, services pages
- Contact form handling

### Authentication Routes
- Laravel Jetstream authentication system
- Login, register, password reset flows
- Email verification routes

### Admin Routes
- Dashboard access (requires authentication)
- Project management (CRUD operations)
- Settings configuration
- Content management interfaces

### Route Patterns
- RESTful resource routes for admin controllers
- Named routes for easier URL generation
- Route model binding for automatic dependency injection
- Middleware groups for authentication and authorization

## API Routes (`api.php`)

Available for:
- AJAX requests from frontend
- Third-party integrations
- Mobile app support (if needed)

## Console Routes (`console.php`)

Custom Artisan commands for:
- Data seeding and migration
- Maintenance tasks
- Automated backups

## Middleware Integration

Routes are protected by:
- `auth` middleware for admin access
- `verified` middleware for email verification
- Custom middleware for specific features

## Error Handling

- Fallback routes for 404 errors
- Custom error pages
- Graceful degradation for missing content

## Route Caching

For production optimization:
```bash
php artisan route:cache
```

## Named Routes

Consistent naming convention:
- `home` - Homepage
- `projects.index` - Project listing
- `projects.show` - Project details
- `admin.*` - Admin panel routes
