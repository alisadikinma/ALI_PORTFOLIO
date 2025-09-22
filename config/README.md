# Config Directory

Laravel application configuration files for Ali's Digital Transformation Portfolio.

## 📁 Structure

```
config/
├── app.php           # Core application configuration
├── auth.php          # Authentication configuration
├── broadcasting.php  # Real-time broadcasting settings
├── cache.php         # Cache store configuration
├── captcha.php       # CAPTCHA system settings
├── cors.php          # Cross-Origin Resource Sharing
├── database.php      # Database connection settings
├── filesystems.php   # File storage configuration
├── fortify.php       # Laravel Fortify authentication
├── hashing.php       # Password hashing configuration
├── jetstream.php     # Laravel Jetstream settings
├── logging.php       # Logging and error reporting
├── mail.php          # Email and notification settings
├── queue.php         # Queue and job processing
├── sanctum.php       # API authentication with Sanctum
├── services.php      # Third-party service integrations
├── session.php       # Session management settings
└── view.php          # View engine and template settings
```

## 🚀 Core Application (`app.php`)

Central application configuration and environment settings.

### Application Identity
```php
'name' => env('APP_NAME', 'Ali Digital Transformation Portfolio'),
'env' => env('APP_ENV', 'production'),
'debug' => (bool) env('APP_DEBUG', false),
'url' => env('APP_URL', 'https://your-domain.com'),
'timezone' => 'Asia/Jakarta',  // Indonesian timezone
'locale' => 'en',              // Primary language
'fallback_locale' => 'en',     // Fallback language
```

### Service Providers
Essential services for portfolio functionality:
- Laravel Framework providers
- Jetstream authentication
- Custom portfolio services
- Third-party integrations (Excel, PDF, QR codes)

### Application Aliases
Commonly used class aliases for easier development.

## 🔐 Authentication (`auth.php`)

Laravel Jetstream authentication configuration.

### Authentication Guards
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### User Providers
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

### Password Reset and Verification
- Email verification requirements
- Password reset token expiration
- Account lockout policies

## 📊 Database Configuration (`database.php`)

Database connections and custom primary key support.

### Primary Connection (MySQL)
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'ali_portfolio'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'engine' => 'InnoDB',
    'options' => [
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
],
```

### Custom Primary Key Support
Configuration supports custom primary keys (`id_project`, `id_setting`) used throughout the portfolio models.

### Connection Pooling and Performance
- Connection persistence settings
- Query caching configuration  
- Database timezone handling

## 🗄️ Cache Configuration (`cache.php`)

Caching strategy for optimal performance.

### Cache Stores
```php
'stores' => [
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
    ],
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
],
```

### Performance Caching
- Route caching for faster request handling
- View compilation caching
- Configuration caching for production
- Application data caching (projects, settings)

## 📁 File Storage (`filesystems.php`)

File storage and upload management.

### Disk Configurations
```php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
        'visibility' => 'private',
    ],
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    'projects' => [
        'driver' => 'local',
        'root' => public_path('images/projects'),
        'url' => env('APP_URL').'/images/projects',
        'visibility' => 'public',
    ],
],
```

### Upload Handling
- Project image storage and management
- Gallery image organization
- Document upload processing
- Temporary file handling

## 📧 Mail Configuration (`mail.php`)

Email system for contact forms and notifications.

### Mail Driver Configuration
```php
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
        'port' => env('MAIL_PORT', 587),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
    ],
],
```

### Email Addresses
```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'noreply@your-domain.com'),
    'name' => env('MAIL_FROM_NAME', 'Ali Digital Transformation'),
],
```

### Email Features
- Contact form submissions
- Admin notifications
- User registration confirmations
- Newsletter functionality (if implemented)

## 🔐 Laravel Jetstream (`jetstream.php`)

Modern authentication and team management.

### Jetstream Features
```php
'features' => [
    Features::termsAndPrivacyPolicy(),
    Features::profilePhotos(),
    Features::api(),
    Features::teams(['invitations' => true]),
    Features::accountDeletion(),
],
```

### Team Management
- Multi-user support for portfolio management
- Role-based access control
- Team invitations and permissions
- Profile photo management

## 🛡️ Laravel Fortify (`fortify.php`)

Backend authentication services.

### Authentication Features
```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

### Security Settings
- Two-factor authentication
- Email verification requirements
- Password confirmation for sensitive operations
- Account recovery options

## 🔒 API Authentication (`sanctum.php`)

Laravel Sanctum for API token management.

### Token Configuration
```php
'expiration' => null,  // Tokens don't expire by default
'middleware' => [
    'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
],
```

### API Security
- Token-based authentication for API endpoints
- CSRF protection for SPA applications
- Cookie encryption and security
- Rate limiting for API endpoints

## 🌐 CORS Configuration (`cors.php`)

Cross-Origin Resource Sharing for API access.

### CORS Settings
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],  // Restrict in production
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

## 📝 Logging Configuration (`logging.php`)

Comprehensive logging and error tracking.

### Log Channels
```php
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
    'error' => [
        'driver' => 'daily',
        'path' => storage_path('logs/error.log'),
        'level' => 'error',
        'days' => 30,
    ],
    'performance' => [
        'driver' => 'daily',
        'path' => storage_path('logs/performance.log'),
        'level' => 'info',
        'days' => 7,
    ],
],
```

### Error Reporting
- Application errors and exceptions
- Performance monitoring and slow queries
- Security events and access attempts
- User activity tracking

## 🔄 Session Management (`session.php`)

User session handling and security.

### Session Configuration
```php
'driver' => env('SESSION_DRIVER', 'file'),
'lifetime' => env('SESSION_LIFETIME', 120),
'expire_on_close' => false,
'encrypt' => true,
'files' => storage_path('framework/sessions'),
'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
'secure' => env('SESSION_SECURE_COOKIE', true),  // HTTPS only
'http_only' => true,  // Prevent XSS
'same_site' => 'lax',  // CSRF protection
```

## 🎨 View Configuration (`view.php`)

Blade template engine settings.

### View Settings
```php
'paths' => [
    resource_path('views'),
],
'compiled' => env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),
```

### Template Optimization
- View caching for production performance
- Blade directive customizations
- Component auto-discovery
- Template compilation optimization

## 🛠️ Third-Party Services (`services.php`)

External service integrations and API configurations.

### Service Integrations
```php
// Email services
'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],

// Google services (Analytics, reCAPTCHA)
'google' => [
    'analytics_id' => env('GOOGLE_ANALYTICS_ID'),
    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
],

// Social media APIs
'social' => [
    'linkedin_api' => env('LINKEDIN_API_KEY'),
    'twitter_api' => env('TWITTER_API_KEY'),
],
```

## 🤖 CAPTCHA Configuration (`captcha.php`)

Anti-spam and bot protection.

### CAPTCHA Settings
```php
'default' => [
    'length' => 5,
    'width' => 120,
    'height' => 36,
    'quality' => 90,
    'math' => false,
    'expire' => 60,
],
```

### Protection Features
- Contact form spam protection
- Registration bot prevention
- Comment system protection
- Admin login security

## ⚡ Queue Configuration (`queue.php`)

Background job processing and task queues.

### Queue Drivers
```php
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
    ],
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
    ],
],
```

### Background Processing
- Email sending queues
- Image processing tasks
- Backup generation jobs
- Analytics data processing

## 📺 Broadcasting (`broadcasting.php`)

Real-time features and WebSocket communication.

### Broadcasting Drivers
```php
'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
        ],
    ],
],
```

### Real-Time Features
- Admin dashboard notifications
- Live contact form submissions
- Real-time analytics updates
- User presence indicators

## 🔧 Environment-Specific Configuration

### Development Settings
```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Production Settings
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database
```

This configuration architecture ensures Ali's Digital Transformation Portfolio runs securely, efficiently, and scalably across all environments! 🚀