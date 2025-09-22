# Routes Directory

URL routing configuration and endpoint definitions for Ali's Digital Transformation Portfolio.

## 📁 Structure

```
routes/
├── web.php              # Main web application routes
├── web_professional.php # Professional routing patterns  
├── web_backup.php       # Backup routing configuration
├── admin.php            # Administrative panel routes
├── admin_simple.php     # Simplified admin routes
├── api.php              # API endpoints and integrations
├── upload.php           # File upload handling routes
├── channels.php         # Broadcasting channel authorization
├── console.php          # Artisan console commands
└── README.md            # This documentation file
```

## 🌐 Web Routes (`web.php`)

Main application routing with comprehensive URL handling.

### Public Access Routes
```php
// Homepage and Core Pages
Route::get('/', [HomeWebController::class, 'index'])->name('home');
Route::get('/about', [HomeWebController::class, 'about'])->name('about');
Route::get('/services', [HomeWebController::class, 'services'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Portfolio and Projects
Route::get('/portfolio', [ProjectController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{slug}', [ProjectController::class, 'show'])->name('portfolio.show');
Route::get('/projects/{category?}', [ProjectController::class, 'category'])->name('projects.category');

// Content Pages  
Route::get('/gallery', [GaleriController::class, 'index'])->name('gallery');
Route::get('/awards', [AwardController::class, 'index'])->name('awards');
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');
Route::get('/news', [BeritaController::class, 'index'])->name('news');
Route::get('/news/{slug}', [BeritaController::class, 'show'])->name('news.show');
```

### Contact and Interaction Routes
```php
// Contact Form Handling
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/success', [ContactController::class, 'success'])->name('contact.success');

// AJAX Endpoints
Route::post('/contact/ajax', [ContactController::class, 'ajaxStore'])->name('contact.ajax');
Route::get('/projects/filter', [ProjectController::class, 'filter'])->name('projects.filter');
```

### SEO and Utility Routes
```php
// SEO and Crawlers
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Dynamic Content
Route::get('/feed.xml', [RssController::class, 'feed'])->name('rss.feed');
Route::get('/manifest.json', [PwaController::class, 'manifest'])->name('pwa.manifest');
```

## 🔐 Authentication Routes (Laravel Jetstream)

### User Authentication
```php
// Jetstream Routes (automatically registered)
Route::middleware(['guest'])->group(function () {
    // Login, Register, Password Reset
    // Email Verification
    // Two-Factor Authentication
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Dashboard Access
    // Profile Management  
    // Account Settings
});
```

## 👨‍💼 Admin Routes (`admin.php`)

Administrative panel with role-based access control.

### Dashboard and Overview
```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/overview', [AdminDashboardController::class, 'overview'])->name('overview');
    
    // Analytics and Reports
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/reports', [AdminReportsController::class, 'index'])->name('reports');
});
```

### Content Management
```php
// Project Management
Route::resource('projects', AdminProjectController::class);
Route::post('projects/{project}/images', [AdminProjectController::class, 'uploadImages']);
Route::delete('projects/{project}/images/{image}', [AdminProjectController::class, 'deleteImage']);

// Content Management
Route::resource('testimonials', AdminTestimonialController::class);
Route::resource('awards', AdminAwardController::class);
Route::resource('news', AdminNewsController::class);
Route::resource('gallery', AdminGalleryController::class);

// Settings Management
Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
```

### User and Access Management
```php
// User Management
Route::resource('users', AdminUserController::class);
Route::post('users/{user}/role', [AdminUserController::class, 'updateRole']);

// Contact Management
Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
```

## 🔌 API Routes (`api.php`)

RESTful API endpoints for external integrations and AJAX requests.

### Public API Endpoints
```php
// Portfolio Data API
Route::get('/projects', [ApiProjectController::class, 'index']);
Route::get('/projects/{id}', [ApiProjectController::class, 'show']);
Route::get('/projects/category/{category}', [ApiProjectController::class, 'byCategory']);

// Contact API
Route::post('/contact', [ApiContactController::class, 'store']);

// Content API  
Route::get('/testimonials', [ApiTestimonialController::class, 'index']);
Route::get('/awards', [ApiAwardController::class, 'index']);
Route::get('/news', [ApiNewsController::class, 'index']);
```

### Administrative API (Protected)
```php
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard Data
    Route::get('/dashboard/stats', [ApiAdminController::class, 'dashboardStats']);
    Route::get('/dashboard/recent', [ApiAdminController::class, 'recentActivity']);
    
    // Quick Actions
    Route::post('/projects/quick-create', [ApiAdminProjectController::class, 'quickCreate']);
    Route::patch('/settings/quick-update', [ApiAdminSettingController::class, 'quickUpdate']);
});
```

## 📁 File Upload Routes (`upload.php`)

Specialized routes for file and media handling.

### Image Upload Management
```php
Route::middleware(['auth'])->prefix('uploads')->group(function () {
    // Project Images
    Route::post('/projects/{project}/images', [UploadController::class, 'projectImages']);
    Route::delete('/projects/images/{image}', [UploadController::class, 'deleteProjectImage']);
    
    // Gallery Images
    Route::post('/gallery/images', [UploadController::class, 'galleryImages']);
    Route::delete('/gallery/images/{image}', [UploadController::class, 'deleteGalleryImage']);
    
    // Document Uploads
    Route::post('/documents', [UploadController::class, 'documents']);
    Route::delete('/documents/{document}', [UploadController::class, 'deleteDocument']);
});
```

## 📺 Broadcasting Routes (`channels.php`)

Real-time communication and WebSocket authorization.

### Channel Authorization
```php
// Private Channels
Broadcast::channel('admin.notifications', function ($user) {
    return $user->isAdmin();
});

// Presence Channels for Admin Dashboard
Broadcast::channel('admin.dashboard', function ($user) {
    return $user->isAdmin() ? ['id' => $user->id, 'name' => $user->name] : null;
});
```

## ⚙️ Console Routes (`console.php`)

Custom Artisan commands for maintenance and automation.

### Maintenance Commands
```php
Artisan::command('portfolio:backup', function () {
    // Backup portfolio data and images
})->purpose('Backup portfolio data');

Artisan::command('portfolio:optimize-images', function () {
    // Optimize and compress portfolio images
})->purpose('Optimize portfolio images');

Artisan::command('portfolio:generate-sitemap', function () {
    // Generate XML sitemap
})->purpose('Generate XML sitemap');
```

### Data Management Commands
```php
Artisan::command('portfolio:seed-demo', function () {
    // Seed demo portfolio data
})->purpose('Seed demo data');

Artisan::command('portfolio:cleanup-orphans', function () {
    // Clean up orphaned images and files
})->purpose('Clean up orphaned files');
```

## 🛡️ Middleware Integration

### Authentication Middleware
- **`auth`**: Require user authentication
- **`admin`**: Require administrator role
- **`verified`**: Require email verification
- **`guest`**: Allow only unauthenticated users

### Custom Middleware
```php
// Rate limiting for contact forms
Route::middleware(['throttle:contact'])->post('/contact', ...);

// CORS handling for API routes  
Route::middleware(['cors'])->prefix('api')->group(...);

// Security headers
Route::middleware(['security.headers'])->group(...);
```

## 🚀 Route Optimization

### Production Optimization
```bash
# Cache routes for better performance
php artisan route:cache

# Clear route cache when updating routes
php artisan route:clear

# List all registered routes
php artisan route:list

# Show routes with specific names or patterns
php artisan route:list --name=admin
php artisan route:list --path=api
```

### Route Model Binding
```php
// Automatic model injection
Route::get('/portfolio/{project:slug}', [ProjectController::class, 'show']);
Route::get('/admin/projects/{project}', [AdminProjectController::class, 'edit']);

// Custom route key names
public function getRouteKeyName()
{
    return 'slug'; // Use slug instead of id for URLs
}
```

## 📊 Route Analytics and Monitoring

### Performance Tracking
- **Response Times**: Monitor route performance
- **Traffic Analysis**: Track popular routes and pages
- **Error Monitoring**: Log and track 404s and errors
- **Conversion Tracking**: Monitor contact form and inquiry routes

### SEO Optimization
- **Clean URLs**: SEO-friendly URL structures
- **Canonical URLs**: Prevent duplicate content issues
- **Structured URLs**: Consistent URL patterns
- **Mobile-Friendly**: Responsive route handling

## 🔧 Development Workflow

### Route Testing
```bash
# Test specific routes
php artisan test --filter=RouteTest

# Test admin routes
php artisan test --group=admin

# Test API endpoints
php artisan test --group=api
```

### Route Documentation
- **Automatic Documentation**: Generate API documentation from routes
- **Route Comments**: Document complex route logic
- **Postman Collections**: API testing and documentation
- **OpenAPI Specifications**: Standardized API documentation

This routing architecture provides comprehensive URL management for Ali's Digital Transformation Portfolio with security, performance, and maintainability! 🚀