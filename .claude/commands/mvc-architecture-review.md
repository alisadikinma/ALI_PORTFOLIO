---
allowed-tools: Grep, LS, Read, Edit, MultiEdit, Write, Glob, Bash
description: Comprehensive Laravel MVC architecture review and compliance assessment
---

# Laravel MVC Architecture Review

You are a Senior Laravel Architect conducting a comprehensive MVC compliance audit. Analyze this Laravel project's architecture, code organization, and adherence to Laravel best practices.

## PROJECT STRUCTURE ANALYSIS

First, analyze the overall project structure:

### **📁 PROJECT DIRECTORY STRUCTURE**
```bash
!`find . -type d -name "app" -o -name "resources" -o -name "routes" -o -name "database" | head -20`
```

### **📂 APP DIRECTORY ANALYSIS**
```bash
!`find app -type f -name "*.php" | head -20`
```

### **🎮 CONTROLLERS INVENTORY**
```bash
!`find app/Http/Controllers -name "*.php" 2>/dev/null || echo "No controllers directory found"`
```

### **📊 MODELS INVENTORY**
```bash
!`find app/Models -name "*.php" 2>/dev/null || find app -name "*.php" -path "*/Models/*" 2>/dev/null || echo "No models found"`
```

### **👁️ VIEWS INVENTORY**
```bash
!`find resources/views -name "*.blade.php" 2>/dev/null | head -15`
```

### **🛣️ ROUTES ANALYSIS**
```bash
!`ls -la routes/ 2>/dev/null || echo "No routes directory found"`
```

## DETAILED MVC COMPLIANCE REVIEW

### **1. MODELS ANALYSIS**

Check each model for Laravel best practices:

```bash
!`find app -name "*.php" -path "*/Models/*" -exec echo "=== {} ===" \; -exec head -30 {} \; 2>/dev/null`
```

**Model Evaluation Criteria:**
- ✅ **Namespace**: Proper `App\Models` namespace
- ✅ **Eloquent Extension**: Extends `Illuminate\Database\Eloquent\Model`
- ✅ **Fillable/Guarded**: Proper mass assignment protection
- ✅ **Relationships**: Correct relationship definitions
- ✅ **Timestamps**: Proper timestamp handling
- ✅ **Casts**: Attribute casting implementation
- ✅ **Table Name**: Following Laravel naming conventions

### **2. CONTROLLERS ANALYSIS**

Review controller structure and implementation:

```bash
!`find app/Http/Controllers -name "*.php" -exec echo "=== {} ===" \; -exec head -50 {} \; 2>/dev/null`
```

**Controller Evaluation Criteria:**
- ✅ **Namespace**: Proper `App\Http\Controllers` namespace
- ✅ **Controller Extension**: Extends `App\Http\Controllers\Controller`
- ✅ **Thin Controllers**: Business logic in services, not controllers
- ✅ **Request Validation**: Using Form Requests or validate() method
- ✅ **Resource Methods**: Following RESTful conventions
- ✅ **Dependency Injection**: Proper DI usage
- ✅ **Response Consistency**: Consistent response patterns

### **3. VIEWS ANALYSIS**

Examine Blade template structure:

```bash
!`find resources/views -name "*.blade.php" -exec echo "=== {} ===" \; -exec head -20 {} \; | head -100`
```

**View Evaluation Criteria:**
- ✅ **Blade Syntax**: Proper use of Blade directives
- ✅ **Template Inheritance**: @extends, @section, @yield usage
- ✅ **Logic-Free**: No business logic in views
- ✅ **Component Usage**: Blade components for reusability
- ✅ **Data Binding**: Proper variable usage
- ✅ **Security**: XSS protection with {{ }} vs {!! !!}

### **4. ROUTES ANALYSIS**

Review route organization and structure:

```bash
!`cat routes/web.php 2>/dev/null | head -50`
```

```bash
!`cat routes/api.php 2>/dev/null | head -30`
```

**Route Evaluation Criteria:**
- ✅ **Organization**: Logical grouping and organization
- ✅ **Middleware**: Proper middleware usage
- ✅ **Resource Routes**: Using Route::resource() where appropriate
- ✅ **Route Model Binding**: Utilizing implicit/explicit binding
- ✅ **Naming**: Consistent route naming conventions
- ✅ **Grouping**: Route groups for common attributes

### **5. MIDDLEWARE ANALYSIS**

Check custom middleware implementation:

```bash
!`find app/Http/Middleware -name "*.php" 2>/dev/null -exec echo "=== {} ===" \; -exec head -30 {} \;`
```

### **6. FORM REQUESTS ANALYSIS**

Review validation implementation:

```bash
!`find app/Http/Requests -name "*.php" 2>/dev/null -exec echo "=== {} ===" \; -exec head -40 {} \;`
```

### **7. SERVICES & REPOSITORIES**

Check for service layer implementation:

```bash
!`find app -name "*Service*.php" -o -name "*Repository*.php" 2>/dev/null -exec echo "=== {} ===" \; -exec head -30 {} \;`
```

## MVC COMPLIANCE SCORING

Evaluate each component and provide scores:

### **MODEL COMPLIANCE SCORE (0-10)**
- File structure and namespacing
- Eloquent relationships
- Mass assignment protection
- Business logic placement

### **CONTROLLER COMPLIANCE SCORE (0-10)**
- Thin controller principle
- Request validation
- Response consistency
- Dependency injection

### **VIEW COMPLIANCE SCORE (0-10)**
- Blade template usage
- Logic separation
- Component architecture
- Security practices

### **ROUTE COMPLIANCE SCORE (0-10)**
- Organization and grouping
- Middleware usage
- RESTful conventions
- Route model binding

## LARAVEL BEST PRACTICES AUDIT

### **🔍 COMMON ISSUES TO CHECK:**

1. **Fat Controllers**: Business logic in controllers instead of services
2. **Database Queries in Views**: Direct DB calls in Blade templates
3. **Missing Validation**: Controllers without proper request validation
4. **Hardcoded Values**: Configuration values hardcoded instead of using config()
5. **Security Issues**: Missing CSRF protection, XSS vulnerabilities
6. **N+1 Queries**: Missing eager loading in relationships
7. **Poor Error Handling**: Inadequate exception handling
8. **Inconsistent Naming**: Not following Laravel naming conventions

### **✅ LARAVEL CONVENTIONS CHECKLIST:**

- **Models**: PascalCase, singular names
- **Controllers**: PascalCase with "Controller" suffix
- **Methods**: camelCase
- **Routes**: kebab-case URLs
- **Views**: kebab-case file names
- **Database**: snake_case table and column names
- **Configuration**: snake_case keys
- **Variables**: camelCase

## FINAL REPORT FORMAT

Provide a comprehensive report with:

1. **Overall MVC Compliance Score** (0-10)
2. **Component-wise Analysis** with individual scores
3. **Critical Issues** identified with severity levels
4. **Specific Recommendations** with code examples
5. **Laravel Best Practices** compliance status
6. **Architecture Improvements** suggestions
7. **Action Items** prioritized by impact

Focus on actionable feedback that will improve code maintainability, security, and Laravel framework adherence.
