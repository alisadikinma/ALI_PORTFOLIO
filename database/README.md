# Database Directory

Database schema, migrations, and seeding configuration.

## Structure

```
database/
├── migrations/      # Database schema versioning
├── seeders/        # Data population scripts
└── factories/      # Model factory definitions
```

## Migrations (`migrations/`)

Schema definitions with Laravel's migration system:

### Core Tables
- Users and authentication (Jetstream)
- Projects portfolio table with custom `id_project` primary key
- Settings table for site configuration
- LookupData table for flexible category/section management

### Key Features
- Custom primary key naming (`id_project`, `id_setting`)
- Image path storage for project galleries
- JSON fields for metadata and flexible data
- Proper foreign key relationships
- Indexes for performance optimization

### Migration Commands
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drops all tables)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

## Seeders (`seeders/`)

Data population for:
- Default site settings and configuration
- Sample portfolio projects for development
- Lookup data for categories and sections
- Admin user accounts

### Seeding Commands
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ProjectSeeder
```

## Factories (`factories/`)

Model factories for testing and development:
- Project factory with realistic portfolio data
- User factory for testing authentication
- Setting factory for configuration testing

## Database Configuration

- **Primary**: MySQL database
- **Session Storage**: File-based (configurable to database)
- **Custom Primary Keys**: Non-standard naming convention
- **Relationships**: Proper Eloquent relationships defined

## Required Environment Variables

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Performance Considerations

- Indexes on frequently queried columns
- Proper relationship definitions to avoid N+1 queries
- Image path storage instead of blob data
- Efficient lookup table structure for categories
