# CLAUDE.md

This file provides high-level guidance to Claude Code when working with this Laravel 10.49.0 portfolio website. For detailed information about specific directories, refer to README.md files in each folder.

## Quick Start

### Development Server
```bash
php artisan serve    # Start Laravel server
npm run dev         # Start Vite development
```

### Claude Code Workflow Commands
```bash
@genz-homepage-review           # Gen Z design assessment
@comprehensive-quality-review   # Full responsive & UX testing
@design-review                  # UI/UX design compliance
@code-review                    # Code quality assessment
@security-review                # Security vulnerability check
```

## Project Structure Overview

### Core Directories (See individual README.md for details)
- **`app/`** - Laravel MVC architecture (Controllers, Models, Services)
- **`resources/`** - Frontend assets (CSS, JS, Blade views)
- **`public/`** - Web-accessible files and compiled assets
- **`database/`** - Migrations, seeders, and schema
- **`routes/`** - URL routing configuration
- **`tests/`** - Pest PHP test suite

### Workflow Directories
- **`.claude/`** - Claude Code commands and agents
- **`.playwright-mcp/`** - Browser testing screenshots and traces

## Architecture Highlights

### Custom Patterns
- **Custom Primary Keys**: `id_project`, `id_setting` (non-standard Laravel)
- **Dynamic Image Handling**: Multiple images per project with auto-cleanup
- **Flexible Configuration**: LookupData model for categories and settings
- **Homepage Sections**: Dynamic section management through database

### Technology Stack
- **Backend**: Laravel 10.49.0 + Jetstream (Livewire)
- **Frontend**: Tailwind CSS + Livewire 3.0 + Vite
- **Database**: MySQL with custom primary keys
- **Testing**: Pest PHP framework
- **Quality Assurance**: MCP Playwright integration

## Known Issues & Focus Areas

### Current Priority Issues
1. **Tablet Responsive Design** - Layout breaks at 768px viewport
2. **Mobile Navigation Bug** - Hamburger menu overlay issues
3. **Gen Z Design Appeal** - Needs modern color palette and typography
4. **Performance Optimization** - Image loading and asset optimization

### Quality Assurance Integration
- **MCP Playwright Tools**: Automated browser testing with screenshot capture
- **Design Compliance**: Style guide and design principles in `/context/`
- **Responsive Testing**: Multi-viewport validation (mobile, tablet, desktop)
- **Gen Z Assessment**: Modern design trend compliance scoring

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

### Development Dependencies
- PHP 8.1+
- Node.js for Vite
- MySQL database
- Composer for PHP dependencies
- NPM for frontend assets

## Workflow Integration

### MCP Playwright Configuration
- `.mcp.json` - Standard Playwright MCP
- `.mcp-executeautomation.json` - Enhanced automation features
- Screenshots automatically saved to `.playwright-mcp/`

### Claude Code Commands
All commands located in `.claude/commands/` with specific focus areas:
- **Quality Reviews**: Responsive design and UX testing
- **Design Assessment**: Gen Z appeal and modern design compliance
- **Code Reviews**: Laravel best practices and security
- **Architecture Reviews**: MVC pattern compliance

## Quick Reference Commands

### Local Development
```bash
# Start local development server
php artisan serve

# Start frontend development with Vite
npm run dev

# Build frontend assets for production
npm run build
```

### Database Management
```bash
# Run database migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drops all tables and re-runs)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Testing
```bash
# Run all tests using Pest
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run a specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with coverage
php artisan test --coverage

# Create new test
php artisan pest:test TestName
```

### Code Quality
```bash
# Format code using Laravel Pint
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test

# Format specific files/directories
./vendor/bin/pint app/Http/Controllers
```

### Code Generation
```bash
# Create new controller
php artisan make:controller ControllerName

# Create model with migration and factory
php artisan make:model ModelName -mf

# Create Livewire component
php artisan make:livewire ComponentName

# Create middleware
php artisan make:middleware MiddlewareName

# Create service provider
php artisan make:provider ProviderName
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Architecture Overview

### MVC Structure
- **Controllers**: Located in `app/Http/Controllers/`, handling HTTP requests and business logic
  - `HomeWebController.php`: Main website homepage controller with homepage sections management
  - `ProjectController.php`: Manages portfolio projects with CRUD operations and image handling
  - `SettingController.php`: Handles site-wide settings and configurations
  - `GaleriController.php`: Manages gallery/media functionality
  - `AwardController.php`: Manages awards and achievements
  - `TestimonialController.php`: Handles client testimonials
  - `BeritaController.php`: News/blog management
  - `ContactController.php`: Contact form handling

### Key Models
- **Project** (`app/Models/Project.php`): Portfolio project model with:
  - Dynamic image handling (multiple images + featured image)
  - Auto-slug generation from project names
  - Scopes for active projects and ordering
  - Automatic image cleanup on deletion
  - Primary key: `id_project`

- **Setting** (`app/Models/Setting.php`): Global site configuration with:
  - Company info, social media links, profile content
  - Statistics (years_experience, project_delivered, etc.)
  - Primary key: `id_setting`

- **LookupData** (`app/Models/LookupData.php`): Flexible lookup/configuration system:
  - Homepage section management (active/inactive, sort order)
  - Project categories
  - Supports hierarchical data with parent_id
  - JSON metadata field for extensibility

### Services Layer
- **HomepageSectionService** (`app/Services/HomepageSectionService.php`): Manages homepage section data
- **SeoService** (`app/Services/SeoService.php`): Handles SEO-related functionality

### Database Configuration
- Primary database: MySQL
- Database name configured in .env (DB_DATABASE)
- Session storage: File-based (configurable)
- Migrations located in `database/migrations/`
- Custom primary keys in models (e.g., `id_project`, `id_setting`)

### Frontend Stack
- **Tailwind CSS**: Primary CSS framework with forms and typography plugins
- **Livewire 3.0**: For reactive components
- **Vite**: Build tool for asset compilation
- **Blade**: Template engine with components in `resources/views/`

### Authentication
- Laravel Jetstream with Livewire implementation
- Session-based authentication with database storage

### Required Environment Variables
```env
APP_KEY=base64:... # Generate with: php artisan key:generate
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
SESSION_DRIVER=file # or database, redis, etc.
```

## Project-Specific Patterns

### Image Handling
- Project images stored in `public/images/projects/`
- Supports multiple images per project with featured image capability
- Automatic URL generation via model accessors
- Image cleanup on project deletion

### Slug Generation
- Automatic slug creation from project names
- Updates slug when project name changes

### View Structure
- Main views in `resources/views/`
- Component-based architecture with Blade components
- Separate admin/dashboard views under `dashboard/` directory

## Routing Architecture
- Main routes defined in `routes/web.php`
- Fallback error handling in home route
- Admin routes typically require authentication middleware
- API routes available in `routes/api.php`

## Middleware
- Standard Laravel middleware in `app/Http/Middleware/`
- Custom middleware includes `DisableScreenCapture.php`
- Authentication handled through Jetstream middleware

## Testing Approach
The project uses Pest PHP for testing with test files located in:
- `tests/Unit/` - Unit tests
- `tests/Feature/` - Feature/integration tests

Configuration in `phpunit.xml` with bootstrap through Pest.php.

## Additional Dependencies
- **maatwebsite/excel**: Excel import/export functionality
- **barryvdh/laravel-dompdf**: PDF generation
- **simplesoftwareio/simple-qrcode**: QR code generation
- **anhskohbo/no-captcha**: Google reCAPTCHA integration
- **greggilbert/recaptcha**: Alternative reCAPTCHA implementation
- **mews/captcha**: Image-based CAPTCHA generation
- **5balloons/laravel-smart-ads**: Advertisement management

## Development Notes
- PHP 8.1+ required
- Uses Laravel Pint for code formatting (PSR-12 standard)
- Custom primary keys used throughout (e.g., `id_project`, `id_setting`)
- Image uploads handled via public storage with automatic cleanup
- Sessions configured for file storage by default

## Visual Development

### Design Principles
- Comprehensive design checklist in `/context/design-principles.md`
- Brand style guide in `/context/style-guide.md`
- When making visual (front-end, UI/UX) changes, always refer to these files for guidance

### Quick Visual Check
IMMEDIATELY after implementing any front-end change:
1. **Identify what changed** - Review the modified components/pages
2. **Navigate to affected pages** - Use `mcp__playwright__browser_navigate` to visit each changed view
3. **Verify design compliance** - Compare against `/context/design-principles.md` and `/context/style-guide.md`
4. **Validate feature implementation** - Ensure the change fulfills the user's specific request
5. **Check acceptance criteria** - Review any provided context files or requirements
6. **Capture evidence** - Take full page screenshot at desktop viewport (1440px) of each changed view
7. **Check for errors** - Run `mcp__playwright__browser_console_messages`

This verification ensures changes meet design standards and user requirements.

### Comprehensive Design Review
Invoke the `@agent-design-review` subagent for thorough design validation when:
- Completing significant UI/UX features
- Before finalizing PRs with visual changes
- Needing comprehensive accessibility and responsiveness testing

## Code Review Guidelines

### Laravel-Specific Review Areas
- **Eloquent Models**: Check relationships, scopes, accessors/mutators
- **Controllers**: Verify proper request validation, authorization, and response handling
- **Migrations**: Ensure proper database schema changes and rollback support
- **Livewire Components**: Check component state management and wire:model bindings
- **Blade Templates**: Verify proper escaping, component usage, and responsive design
- **Service Classes**: Check separation of concerns and dependency injection

### Security Focus Areas
- **Authentication**: Jetstream implementation and user session management
- **Authorization**: Gate and policy definitions
- **Input Validation**: Form requests and validation rules
- **File Uploads**: Image handling in Project model, path traversal prevention
- **SQL Injection**: Eloquent usage vs raw queries
- **XSS Prevention**: Blade template escaping and user-generated content
- **CSRF Protection**: Form token usage
- **reCAPTCHA**: Proper implementation and validation

### Performance Considerations
- **Database Queries**: N+1 query problems, eager loading
- **Image Optimization**: Project image sizes and formats
- **Asset Compilation**: Vite build optimization
- **Caching**: Route, config, and view caching in production


## Visual Development

### Design Principles
- Comprehensive design checklist in `/context/design-principles.md`
- Brand style guide in `/context/style-guide.md`
- When making visual (front-end, UI/UX) changes, always refer to these files for guidance

### Quick Visual Check
IMMEDIATELY after implementing any front-end change:
1. **Identify what changed** - Review the modified components/pages
2. **Navigate to affected pages** - Use `mcp__playwright__browser_navigate` to visit each changed view
3. **Verify design compliance** - Compare against `/context/design-principles.md` and `/context/style-guide.md`
4. **Validate feature implementation** - Ensure the change fulfills the user's specific request
5. **Check acceptance criteria** - Review any provided context files or requirements
6. **Capture evidence** - Take full page screenshot at desktop viewport (1440px) of each changed view
7. **Check for errors** - Run `mcp__playwright__browser_console_messages`

This verification ensures changes meet design standards and user requirements.

### Comprehensive Design Review
Invoke the `@agent-design-review` subagent for thorough design validation when:
- Completing significant UI/UX features
- Before finalizing PRs with visual changes
- Needing comprehensive accessibility and responsiveness testing
