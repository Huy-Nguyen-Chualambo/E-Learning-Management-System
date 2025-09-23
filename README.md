# E-Learning Management System

A comprehensive E-Learning Management System built with Laravel 9, featuring a 5-layer architecture (MVC + Repository + Service), advanced authentication & authorization, and modern frontend with AJAX.

## Features

### Core Functionality
- **User Management**: Complete CRUD operations with role-based permissions
- **Role & Permission System**: Custom implementation without Spatie package
- **Category Management**: Hierarchical categories with 2-level depth
- **Product Management**: Course management with image handling
- **Advanced Search**: Debounced search with multiple filters

### Technical Features
- **5-Layer Architecture**: MVC + Repository + Service pattern
- **Custom Validation**: Email validation for @deha-soft.com domain
- **Image Handling**: Laravel Image Intervention with custom trait
- **AJAX Operations**: All CRUD operations use AJAX with Bootstrap modals
- **Local Scopes**: Advanced query building with Eloquent scopes
- **Middleware**: Custom permission and role checking middleware
- **Testing**: Comprehensive feature tests

## Installation

### Prerequisites
- PHP 8.0+
- Composer
- MySQL/PostgreSQL
- Node.js (for frontend assets)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd E-Learning-Management-System/laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Run the application**
   ```bash
   php artisan serve
   ```

## Default Credentials

After running seeders, you'll have:
- **Super Admin**: superadmin@deha-soft.com
- **Password**: password

## Architecture

### Models & Relationships
- **Users** ↔ **Roles** (Many-to-Many)
- **Roles** ↔ **Permissions** (Many-to-Many)
- **Products** ↔ **Categories** (Many-to-Many)
- **Categories** ↔ **Categories** (Self-referencing, max 2 levels)

### Repository Pattern
- `BaseRepositoryInterface` - Base contract for all repositories
- `BaseRepository` - Base implementation with common CRUD operations
- Specific repositories for each model with custom methods

### Service Layer
- Business logic encapsulation
- Validation handling
- Image processing
- Data transformation

### Custom Components
- **HandleImage Trait**: Image upload, resize, and deletion
- **DehaSoftEmail Rule**: Custom validation for email domain
- **CheckPermission Middleware**: Role and permission verification
- **Local Scopes**: Query optimization and filtering

## API Endpoints

### Users
- `GET /api/users` - List all users
- `POST /api/users` - Create user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user
- `GET /api/users/search` - Search users

### Products
- `GET /api/products` - List all products
- `POST /api/products` - Create product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product
- `GET /api/products/search` - Search products
- `GET /api/products/featured` - Get featured products

### Categories
- `GET /api/categories` - List all categories
- `POST /api/categories` - Create category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category
- `GET /api/categories/search` - Search categories

## Testing

Run the test suite:
```bash
php artisan test
```

The test suite includes:
- User management tests
- Product management tests
- Permission system tests
- API endpoint tests

## Code Quality

The project follows:
- **PSR-2** coding standards
- **Laravel Best Practices**
- **Clean Code** principles
- **SOLID** principles

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License.
