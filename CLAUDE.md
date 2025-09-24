# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Local Development
```bash
php artisan serve    # Start Laravel server
npm run dev         # Start Vite development
npm run build       # Build for production
```

### Testing
```bash
php artisan test                        # Run all tests using Pest
php artisan test --testsuite=Unit       # Run unit tests only
php artisan test --testsuite=Feature    # Run feature tests only
php artisan test --coverage             # Run with coverage report
vendor/bin/pest                         # Direct Pest execution
vendor/bin/pest --parallel              # Run tests in parallel
```

### Code Quality
```bash
./vendor/bin/pint                       # Format code using Laravel Pint
./vendor/bin/pint --test                # Check code style without fixing
```

### Database
```bash
php artisan migrate                     # Run migrations
php artisan migrate:fresh               # Fresh migration (drops all tables)
php artisan migrate:rollback            # Rollback migrations
php artisan db:seed                     # Run database seeders
# Database connection: portfolio_db (MySQL/MariaDB via XAMPP)
# Custom command: "C:\xampp\mysql\bin\mysql.exe" -u root portfolio_db
```

### Cache Management
```bash
php artisan cache:clear                 # Clear application cache
php artisan config:clear                # Clear configuration cache
php artisan route:clear                 # Clear route cache
php artisan view:clear                  # Clear compiled views
php artisan optimize:clear              # Clear all cached files at once
```

## Architecture Overview

### Technology Stack
- **Laravel 10.49.0** with Jetstream (Livewire)
- **Frontend**: Tailwind CSS + Livewire 3.0 + Vite
- **Database**: MySQL with custom primary keys
- **Testing**: Pest PHP framework
- **Code Quality**: Laravel Pint for formatting
- **Additional Packages**: Excel import/export, PDF generation, QR codes, CAPTCHA, Smart Ads

### Key Patterns
- **Custom Primary Keys**: Uses `id_project`, `id_setting` instead of standard Laravel `id`
- **Dynamic Image Handling**: Project model supports multiple images with auto-cleanup
- **LookupData Model**: Flexible configuration system for categories and settings
- **Homepage Sections**: Database-driven section management

### Key Models
- **Project** (`app/Models/Project.php`): Primary key `id_project`, handles multiple images, auto-slug generation, automatic image cleanup, LONGTEXT fields for images and descriptions
- **Setting** (`app/Models/Setting.php`): Primary key `id_setting`, global site configuration including social media links
- **Layanan** (`app/Models/Layanan.php`): Primary key `id_layanan`, services management with sequence ordering
- **LookupData** (`app/Models/LookupData.php`): Flexible configuration system for categories and dropdown values
- **User, Award, Testimonial, Berita, Contact, Galeri**: All following custom primary key pattern

## Environment Setup

### Required Environment Variables
```env
APP_KEY=base64:...              # php artisan key:generate
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
SESSION_DRIVER=file
```

### Dependencies
- PHP 8.1+, Node.js, MySQL
- Laravel Jetstream with Livewire
- Pest PHP for testing
- Laravel Pint for code formatting

## Important Notes

### Custom Primary Keys
All models use custom primary keys (e.g., `id_project`, `id_setting`) instead of Laravel's default `id`. This affects:
- Model definitions
- Foreign key relationships
- Route model binding

### Image Handling
- Project images stored in `public/images/projects/`
- Automatic cleanup on deletion
- Support for multiple images plus featured image

### Routing Architecture
- **Emergency Fallback Pattern**: Routes include try-catch blocks with fallback views for resilience
- **Controller-based routing**: Direct controller instantiation in route closures for critical routes
- **Portfolio-specific URLs**: `/portfolio/all` for complete portfolio listing
- **Performance Optimization**: Homepage data cached for 30 minutes, site config cached for 5 minutes

### Multi-Agent System
The `.claude/` directory contains a sophisticated multi-agent orchestration system with specialized agents for code review, security auditing, architecture assessment, performance analysis, accessibility testing, and UI design. Includes execution phases and comprehensive reporting system. Available slash commands: `/code-review`, `/design-review`, `/security-review`, `/orchestrate`.

## Key Controllers
- **HomeWebController**: Homepage sections management with emergency fallbacks
- **ProjectController**: Portfolio projects with image handling
- **SettingController**: Site-wide configuration
- **LayananController**: Services management
- **GaleriController**, **AwardController**, **TestimonialController**, **BeritaController**, **ContactController**: Feature-specific controllers

## Additional Dependencies
- Excel import/export (maatwebsite/excel)
- PDF generation (barryvdh/laravel-dompdf)
- QR codes (simplesoftwareio/simple-qrcode)
- reCAPTCHA (anhskohbo/no-captcha, greggilbert/recaptcha)
- Image CAPTCHA (mews/captcha)
- Smart ads (5balloons/laravel-smart-ads)

## Development Tools
- **Laravel Debugbar**: Available in development for debugging
- **Laravel Telescope**: Advanced debugging and monitoring
- **Vite**: Modern frontend build tool with HMR and production minification
- **XAMPP**: Local development environment (MySQL via C:\xampp\mysql\bin\mysql.exe)

## Development Environment
- **Windows-based**: XAMPP stack with MariaDB/MySQL
- **Database**: portfolio_db accessible via XAMPP MySQL
- **Frontend Build**: Vite with Tailwind CSS, minification enabled
- **Test Database**: Uses same MySQL connection (not in-memory SQLite)