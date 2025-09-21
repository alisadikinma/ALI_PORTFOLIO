# Tests Directory

Test suite configuration and test cases for quality assurance.

## Structure

```
tests/
├── Feature/        # Integration and feature tests
├── Unit/          # Unit tests for individual components
├── TestCase.php   # Base test class
└── CreatesApplication.php
```

## Testing Framework

- **Pest PHP** - Modern testing framework for PHP
- **PHPUnit** - Underlying test runner
- Configuration in `phpunit.xml`

## Feature Tests (`Feature/`)

Integration tests covering:
- HTTP request/response cycles
- Authentication flows
- Database interactions
- Form submissions and validation
- File uploads and image handling

### Example Test Areas
- Homepage loading and content display
- Project CRUD operations through web interface
- Contact form functionality
- Admin panel access and operations
- API endpoint responses (if applicable)

## Unit Tests (`Unit/`)

Isolated component testing:
- Model methods and relationships
- Service class functionality
- Helper functions and utilities
- Validation rules
- Custom middleware logic

### Example Test Areas
- Project model slug generation
- Image cleanup on model deletion
- Settings model configuration handling
- LookupData hierarchy relationships

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage

# Create new test
php artisan pest:test TestName
```

## Test Database

- Uses SQLite in-memory database for speed
- Fresh database for each test run
- Database transactions for isolation
- Factory data for consistent testing

## Continuous Integration

Tests should be run:
- Before each deployment
- On pull request creation
- As part of CI/CD pipeline
- Before major feature releases

## Coverage Goals

Target coverage areas:
- All controller methods
- Critical model functionality
- Service layer business logic
- Authentication and authorization
- File upload and image handling

## Test Data

- Uses model factories for consistent test data
- Database seeders for complex scenarios
- Mocked external services where appropriate
