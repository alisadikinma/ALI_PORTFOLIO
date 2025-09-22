# Models Directory

Eloquent models representing the data structure and business logic for Ali's Digital Transformation Portfolio.

## 📁 Structure

```
app/Models/
├── User.php          # User authentication and management
├── Project.php       # Portfolio projects (custom id_project)
├── Setting.php       # Site configuration (custom id_setting)
├── LookupData.php    # Flexible lookup/category system
├── Contact.php       # Contact form submissions
├── Testimonial.php   # Client testimonials and reviews
├── Award.php         # Professional achievements
├── Galeri.php        # Gallery collections
├── GalleryItem.php   # Individual gallery items
├── Berita.php        # News/blog articles
└── Layanan.php       # Services offered
```

## 🏗️ Model Architecture

### Core Design Principles
- **Custom Primary Keys**: Uses `id_project`, `id_setting` instead of standard Laravel `id`
- **Business Logic Encapsulation**: Models handle domain-specific logic
- **Relationship Management**: Proper Eloquent relationships between entities
- **Data Validation**: Model-level validation and business rules
- **Performance Optimization**: Query scopes and eager loading support

## 👤 User Model (`User.php`)

Laravel Jetstream user authentication and management.

### Key Features
```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, HasTeams, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email', 
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

### User Capabilities
- **Authentication**: Login, registration, password reset
- **Profile Management**: Profile photos and personal information
- **Two-Factor Auth**: Enhanced security with 2FA
- **Team Management**: Multi-user portfolio administration
- **API Access**: Sanctum token-based API authentication

## 📁 Project Model (`Project.php`)

Core portfolio project management with custom primary key.

### Model Structure
```php
class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_project';  // Custom primary key
    
    protected $fillable = [
        'project_name',
        'project_description',
        'project_category',
        'project_client',
        'project_technologies',
        'project_url',
        'project_images',
        'featured_image',
        'project_status',
        'project_completion_date',
        'is_featured',
        'slug',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'project_images' => 'array',
        'project_technologies' => 'array',
        'project_completion_date' => 'date',
        'is_featured' => 'boolean',
    ];
}
```

### Key Features
- **Image Management**: Multiple images with featured image designation
- **SEO Optimization**: Auto-generated slugs and meta information
- **Technology Stack**: Array storage for project technologies
- **Status Tracking**: Project completion status and dates
- **Featured Projects**: Highlight important portfolio pieces
- **Client Information**: Client details and project context

### Business Logic
```php
// Automatic slug generation
public function setProjectNameAttribute($value)
{
    $this->attributes['project_name'] = $value;
    $this->attributes['slug'] = Str::slug($value);
}

// Image URL generation
public function getFeaturedImageUrlAttribute()
{
    return $this->featured_image 
        ? asset('images/projects/' . $this->featured_image)
        : asset('images/default-project.jpg');
}

// Technology badge generation
public function getTechnologyBadgesAttribute()
{
    return collect($this->project_technologies)->map(function ($tech) {
        return [
            'name' => $tech,
            'color' => $this->getTechnologyColor($tech)
        ];
    });
}
```

### Query Scopes
```php
public function scopeFeatured($query)
{
    return $query->where('is_featured', true);
}

public function scopeCompleted($query)
{
    return $query->where('project_status', 'completed');
}

public function scopeByCategory($query, $category)
{
    return $query->where('project_category', $category);
}
```

## ⚙️ Setting Model (`Setting.php`)

Global site configuration with custom primary key.

### Configuration Structure
```php
class Setting extends Model
{
    protected $primaryKey = 'id_setting';  // Custom primary key
    
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'setting_group',
        'setting_description',
        'is_active',
    ];

    protected $casts = [
        'setting_value' => 'json',
        'is_active' => 'boolean',
    ];
}
```

### Setting Categories
- **Company Information**: Name, description, contact details
- **Social Media**: Links to social platforms
- **SEO Settings**: Meta tags, analytics codes
- **Contact Information**: Addresses, phone numbers, emails
- **Business Statistics**: Followers, experience, achievements
- **Theme Settings**: Colors, layouts, preferences

### Helper Methods
```php
public static function get($key, $default = null)
{
    $setting = static::where('setting_key', $key)
                    ->where('is_active', true)
                    ->first();
    
    return $setting ? $setting->setting_value : $default;
}

public static function set($key, $value, $group = 'general')
{
    return static::updateOrCreate(
        ['setting_key' => $key],
        [
            'setting_value' => $value,
            'setting_group' => $group,
            'is_active' => true,
        ]
    );
}
```

## 🔍 LookupData Model (`LookupData.php`)

Flexible lookup and categorization system.

### Lookup Structure
```php
class LookupData extends Model
{
    protected $table = 'lookup_data';
    
    protected $fillable = [
        'lookup_type',
        'lookup_code',
        'lookup_value',
        'lookup_description',
        'parent_id',
        'sort_order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];
}
```

### Lookup Categories
- **Project Categories**: Development, Design, Consulting, Strategy
- **Technologies**: Programming languages, frameworks, tools
- **Service Types**: Digital transformation service categories
- **Homepage Sections**: Dynamic section management
- **Contact Reasons**: Inquiry categorization
- **Award Types**: Achievement categorization

### Hierarchical Support
```php
public function parent()
{
    return $this->belongsTo(LookupData::class, 'parent_id');
}

public function children()
{
    return $this->hasMany(LookupData::class, 'parent_id');
}

public function scopeByType($query, $type)
{
    return $query->where('lookup_type', $type)->where('is_active', true);
}
```

## 📬 Contact Model (`Contact.php`)

Contact form submissions and inquiry management.

### Contact Structure
```php
class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'inquiry_type',
        'status',
        'ip_address',
        'user_agent',
        'source',
        'is_read',
        'admin_notes',
        'replied_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];
}
```

### Contact Management
- **Inquiry Tracking**: Status and response management
- **Admin Notes**: Internal notes and follow-up information
- **Source Tracking**: Track inquiry sources and campaigns
- **Security**: IP address and user agent logging
- **Response Management**: Track admin responses

### Business Logic
```php
public function markAsRead()
{
    $this->update(['is_read' => true]);
}

public function markAsReplied()
{
    $this->update([
        'status' => 'replied',
        'replied_at' => now(),
    ]);
}

public function scopeUnread($query)
{
    return $query->where('is_read', false);
}

public function scopePending($query)
{
    return $query->where('status', 'pending');
}
```

## 🏆 Testimonial Model (`Testimonial.php`)

Client testimonials and reviews management.

### Testimonial Structure
```php
class Testimonial extends Model
{
    protected $fillable = [
        'client_name',
        'client_position',
        'client_company',
        'client_image',
        'testimonial_text',
        'rating',
        'project_id',
        'is_featured',
        'is_approved',
        'display_order',
        'created_date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'created_date' => 'date',
    ];
}
```

### Testimonial Features
- **Client Information**: Name, position, company details
- **Rating System**: 5-star rating system
- **Project Association**: Link testimonials to specific projects
- **Approval Workflow**: Admin moderation before display
- **Featured Testimonials**: Highlight important reviews
- **Display Ordering**: Control testimonial presentation order

### Relationships
```php
public function project()
{
    return $this->belongsTo(Project::class, 'project_id', 'id_project');
}

public function scopeFeatured($query)
{
    return $query->where('is_featured', true)->where('is_approved', true);
}

public function scopeApproved($query)
{
    return $query->where('is_approved', true);
}
```

## 🏆 Award Model (`Award.php`)

Professional achievements and recognition management.

### Award Structure
```php
class Award extends Model
{
    protected $fillable = [
        'award_title',
        'award_description',
        'awarding_organization',
        'award_date',
        'award_image',
        'award_certificate',
        'award_category',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'award_date' => 'date',
        'is_featured' => 'boolean',
    ];
}
```

### Award Management
- **Achievement Tracking**: Professional awards and recognitions
- **Organization Details**: Awarding body information
- **Documentation**: Certificates and proof images
- **Categorization**: Group awards by type or industry
- **Featured Awards**: Highlight important achievements

## 🖼️ Gallery Models

### Galeri Model (`Galeri.php`)
Gallery collection management for organizing media.

### GalleryItem Model (`GalleryItem.php`)
Individual gallery items with metadata.

```php
// Gallery collection
class Galeri extends Model
{
    protected $fillable = [
        'gallery_name',
        'gallery_description',
        'gallery_category',
        'is_active',
        'display_order',
    ];
}

// Individual gallery items
class GalleryItem extends Model
{
    protected $fillable = [
        'gallery_id',
        'item_title',
        'item_description',
        'item_image',
        'item_type',
        'display_order',
    ];

    public function gallery()
    {
        return $this->belongsTo(Galeri::class);
    }
}
```

## 📰 News Model (`Berita.php`)

Blog and news article management.

### Article Structure
```php
class Berita extends Model
{
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'tags',
        'author_id',
        'published_at',
        'is_published',
        'slug',
        'meta_title',
        'meta_description',
        'view_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];
}
```

### Content Features
- **SEO Optimization**: Slugs, meta tags, structured data
- **Category System**: Article categorization
- **Tag Management**: Flexible tagging system
- **Publication Control**: Draft and publish workflow
- **View Tracking**: Article popularity metrics
- **Author Attribution**: Author management

## 🛠️ Services Model (`Layanan.php`)

Service offerings and capability management.

### Service Structure
```php
class Layanan extends Model
{
    protected $fillable = [
        'service_name',
        'service_description',
        'service_features',
        'service_price',
        'service_category',
        'service_icon',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'service_features' => 'array',
        'is_featured' => 'boolean',
    ];
}
```

## 🔄 Model Relationships

### Cross-Model Relationships
```php
// Project has many testimonials
class Project extends Model
{
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'project_id', 'id_project');
    }
}

// User has many contacts (if user system is implemented)
class User extends Model
{
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}

// Settings can be grouped
class Setting extends Model
{
    public function scopeByGroup($query, $group)
    {
        return $query->where('setting_group', $group);
    }
}
```

## 🚀 Performance Optimization

### Query Optimization
- **Eager Loading**: Prevent N+1 query problems
- **Query Scopes**: Reusable query logic
- **Database Indexes**: Optimized for frequent queries
- **Caching**: Model result caching for expensive queries

### Model Caching Example
```php
class Project extends Model
{
    public static function getFeaturedProjects()
    {
        return Cache::remember('featured_projects', 3600, function () {
            return static::featured()
                         ->with(['testimonials'])
                         ->orderBy('display_order')
                         ->get();
        });
    }
}
```

This model architecture provides robust, scalable data management for Ali's Digital Transformation Portfolio with proper business logic encapsulation and performance optimization! 🚀