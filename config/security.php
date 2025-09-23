<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines security headers that should be applied
    | to protect against common web vulnerabilities.
    |
    */

    'headers' => [
        'X-Frame-Options' => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:; object-src 'none';",
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for secure file uploads including allowed file types,
    | maximum file sizes, and security scanning options.
    |
    */

    'upload' => [
        'max_size' => 2048, // KB
        'allowed_image_types' => ['jpeg', 'jpg', 'png', 'webp'],
        'allowed_document_types' => ['pdf'],
        'scan_uploads' => env('SCAN_UPLOADS', false),
        'quarantine_suspicious' => env('QUARANTINE_SUSPICIOUS', true),
        'upload_path' => 'uploads/secure/',
        'temp_path' => 'uploads/temp/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for database security including query logging,
    | connection security, and access monitoring.
    |
    */

    'database' => [
        'log_queries' => env('LOG_DB_QUERIES', false),
        'log_slow_queries' => env('LOG_SLOW_QUERIES', true),
        'slow_query_threshold' => env('SLOW_QUERY_THRESHOLD', 1000), // milliseconds
        'enable_query_monitoring' => env('ENABLE_QUERY_MONITORING', true),
        'max_connections' => env('DB_MAX_CONNECTIONS', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for authentication security including rate limiting,
    | password policies, and session security.
    |
    */

    'auth' => [
        'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration' => env('LOCKOUT_DURATION', 900), // seconds (15 minutes)
        'password_min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_password_complexity' => env('REQUIRE_PASSWORD_COMPLEXITY', true),
        'session_timeout' => env('SESSION_TIMEOUT', 3600), // seconds (1 hour)
        'force_password_change' => env('FORCE_PASSWORD_CHANGE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for admin-specific security measures including
    | IP restrictions, additional authentication, and audit logging.
    |
    */

    'admin' => [
        'allowed_ips' => env('ADMIN_ALLOWED_IPS', null), // comma-separated IPs
        'require_2fa' => env('ADMIN_REQUIRE_2FA', false),
        'log_admin_actions' => env('LOG_ADMIN_ACTIONS', true),
        'admin_session_timeout' => env('ADMIN_SESSION_TIMEOUT', 1800), // 30 minutes
        'admin_email_whitelist' => env('ADMIN_EMAIL_WHITELIST', 'admin@aliportfolio.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for content filtering, input validation,
    | and output sanitization.
    |
    */

    'content' => [
        'enable_html_purifier' => env('ENABLE_HTML_PURIFIER', true),
        'allowed_html_tags' => ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a', 'img', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        'max_input_length' => env('MAX_INPUT_LENGTH', 10000),
        'enable_content_scanning' => env('ENABLE_CONTENT_SCANNING', false),
        'block_suspicious_patterns' => env('BLOCK_SUSPICIOUS_PATTERNS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Alerting Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for security monitoring, logging, and alerting
    | of suspicious activities.
    |
    */

    'monitoring' => [
        'enable_security_logging' => env('ENABLE_SECURITY_LOGGING', true),
        'log_failed_attempts' => env('LOG_FAILED_ATTEMPTS', true),
        'log_admin_access' => env('LOG_ADMIN_ACCESS', true),
        'alert_on_suspicious_activity' => env('ALERT_SUSPICIOUS_ACTIVITY', false),
        'alert_email' => env('SECURITY_ALERT_EMAIL', 'security@aliportfolio.com'),
        'log_retention_days' => env('LOG_RETENTION_DAYS', 90),
    ],

];