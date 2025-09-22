# Database Directory

Database schema, migrations, seeders, and factory definitions for Ali's Digital Transformation Portfolio.

## 📁 Structure

```
database/
├── migrations/      # Database schema versioning and structure
├── seeders/        # Data population and initialization scripts
├── factories/      # Model factory definitions for testing
└── .gitignore      # Git ignore rules for database files
```

## 🗄️ Database Architecture

### Core Design Principles
- **Custom Primary Keys**: Uses `id_project`, `id_setting` instead of standard Laravel `id`
- **Flexible Schema**: JSON fields for extensible metadata storage
- **Image Management**: Path-based storage with automatic cleanup
- **Performance Optimized**: Proper indexing and relationship design
- **Business Logic Separation**: Database handles data, models handle business logic

### Database Schema Overview

#### **Core Business Tables**
- **`projects`** - Portfolio projects with `id_project` primary key
- **`settings`** - Global site configuration with `id_setting` primary key  
- **`lookup_data`** - Flexible lookup system for categories and sections
- **`contacts`** - Contact form submissions and inquiries
- **`testimonials`** - Client testimonials and reviews
- **`awards`** - Professional achievements and recognitions
- **`galleries`** - Media galleries and image collections
- **`news`** - Blog posts and news articles

#### **System Tables** (Laravel/Jetstream)
- **`users`** - User authentication and management
- **`sessions`** - User session management
- **`personal_access_tokens`** - API token management
- **`failed_jobs`** - Failed queue job tracking

## 🔄 Migrations (`migrations/`)

Laravel's database versioning system for schema management.

### Migration Categories

#### **Initial Schema Migrations**
- User authentication tables (Jetstream)
- Core portfolio tables (projects, settings)
- Lookup and configuration tables

#### **Enhancement Migrations** 
- Additional columns for SEO optimization
- Performance indexes and constraints
- New feature tables (testimonials, awards, etc.)

#### **Custom Primary Key Implementation**
```php
// Example migration pattern
Schema::create('projects', function (Blueprint $table) {
    $table->id('id_project');  // Custom primary key
    $table->string('project_name');
    $table->text('project_description');
    $table->json('project_images')->nullable();
    $table->timestamps();
});
```

### Migration Commands
```bash
# Run all pending migrations
php artisan migrate

# Run migrations with output details
php artisan migrate --verbose

# Rollback last batch of migrations
php artisan migrate:rollback

# Rollback specific number of batches
php artisan migrate:rollback --step=3

# Fresh migration (WARNING: Drops all tables)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status

# Create new migration
php artisan make:migration create_table_name
```

## 🌱 Seeders (`seeders/`)

Database population scripts for development and production setup.

### Seeder Types

#### **Development Seeders**
- **`ProjectSeeder`** - Sample portfolio projects with realistic data
- **`TestimonialSeeder`** - Client testimonials and reviews
- **`GallerySeeder`** - Sample gallery images and media

#### **Production Seeders**
- **`SettingSeeder`** - Essential site configuration (company info, contact details)
- **`LookupDataSeeder`** - System lookup values (categories, types, sections)
- **`AdminUserSeeder`** - Default admin user account

#### **Configuration Seeders**
- **`HomepageSectionSeeder`** - Dynamic homepage section data
- **`SeoDataSeeder`** - SEO meta information and structured data
- **`ContactPageSeeder`** - Contact page content and settings

### Seeding Commands
```bash
# Run all seeders
php artisan db:seed

# Run specific seeder class
php artisan db:seed --class=ProjectSeeder

# Run seeders in production mode
php artisan db:seed --force

# Create new seeder
php artisan make:seeder TableNameSeeder
```

### Seeder Best Practices
- **Idempotent**: Can be run multiple times safely
- **Environment Aware**: Different data for development vs production
- **Relationship Handling**: Properly handles foreign key relationships
- **Image Handling**: Includes sample images for development

## 🏭 Factories (`factories/`)

Model factories for generating test data and development content.

### Available Factories

#### **Core Business Factories**
- **`ProjectFactory`** - Generates realistic portfolio projects
  - Random project names and descriptions
  - Technology stacks and client information
  - Project images and gallery data
  - Completion dates and status

- **`SettingFactory`** - Site configuration testing data
  - Company information variations
  - Contact details and social links
  - SEO settings and meta information

- **`TestimonialFactory`** - Client testimonial generation
  - Realistic client names and companies
  - Testimonial content and ratings
  - Project associations and dates

### Factory Usage
```php
// Create single instance
$project = Project::factory()->create();

// Create multiple instances
$projects = Project::factory()->count(10)->create();

// Create with custom attributes
$project = Project::factory()->create([
    'project_name' => 'Custom Project Name',
    'is_featured' => true
]);

// Create related models
$project = Project::factory()
    ->hasTestimonials(3)
    ->create();
```

## ⚙️ Database Configuration

### Environment Setup
```env
# MySQL Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ali_portfolio
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Optional: Database URL format
# DATABASE_URL=mysql://user:password@host:port/database
```

### Connection Options
```php
// config/database.php MySQL configuration
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'engine' => 'InnoDB',
]
```

## 🚀 Performance Optimizations

### Database Indexing Strategy
- **Primary Keys**: Custom naming with proper indexing
- **Foreign Keys**: Indexed for relationship queries
- **Search Fields**: Full-text indexes on searchable content
- **Lookup Tables**: Optimized for frequent category queries

### Query Optimization
- **Eager Loading**: Prevent N+1 query problems
- **Relationship Caching**: Cache frequently accessed relationships
- **Index Usage**: Proper indexes on WHERE clause columns
- **Query Scopes**: Reusable query logic in models

### Image Storage Strategy
- **Path-based Storage**: Store image paths, not binary data
- **Automatic Cleanup**: Remove orphaned images on deletion
- **Optimized Structure**: JSON fields for image metadata
- **CDN Ready**: Structure supports CDN integration

## 🔧 Custom Features

### Custom Primary Keys
The portfolio uses custom primary key naming:
```php
// Models specify custom primary keys
protected $primaryKey = 'id_project';
protected $primaryKey = 'id_setting';
```

### Flexible Lookup System
The `lookup_data` table provides:
- Hierarchical category system
- Dynamic homepage section management
- Extensible metadata storage
- Multi-purpose lookup values

### Image Management
Sophisticated image handling:
- Multiple images per project
- Featured image designation  
- Automatic path generation
- Cleanup on model deletion

## 🧪 Testing Integration

### Factory Integration
- Realistic test data generation
- Consistent with production schema
- Supports relationship testing
- Configurable data variations

### Test Database
```bash
# Use separate test database
php artisan config:clear
php artisan migrate --env=testing
php artisan db:seed --env=testing
```

## 📋 Maintenance Tasks

### Regular Maintenance
```bash
# Optimize database tables
php artisan migrate:optimize

# Clear compiled views and cache
php artisan view:clear
php artisan cache:clear

# Backup database (add to cron)
mysqldump -u user -p database > backup.sql
```

### Monitoring Queries
```php
// Enable query logging in development
DB::enableQueryLog();
// ... run operations
dd(DB::getQueryLog());
```

This database architecture supports Ali's Digital Transformation Portfolio with flexible, scalable, and performant data management! 🚀