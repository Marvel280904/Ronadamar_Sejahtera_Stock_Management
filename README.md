# Stock Management System

A comprehensive stock management system built with Laravel for inventory tracking and management.

## 🚀 Features

- **Product Management** - Full CRUD operations for products
- **Stock Control** - Add/Reduce stock with history tracking
- **RESTful API** - Complete API endpoints for integration
- **Real-time Notifications** - Stock alerts and monthly reminders
- **PDF Reports** - Generate stock reports
- **User Authentication** - Role-based access control
- **Modern UI** - Responsive design with Tailwind CSS

## 🛠️ Technology Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **PDF Generation:** DomPDF

## 📦 API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get user info

### Products
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get product details
- `POST /api/products` - Create new product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

### Stock Management
- `POST /api/products/{id}/add-stock` - Add stock
- `POST /api/products/{id}/reduce-stock` - Reduce stock

### Categories
- `GET /api/categories` - List all categories

## 🏁 Installation

1. **Clone the repository:**
git clone https://github.com/USERNAME/stock-management.git
cd stock-management

2. **Install dependencies:**
composer install
npm install

3. **Setup environment:**
cp .env.example .env
php artisan key:generate

4. **Configure database in .env**
DB_PASSWORD= isi dengan password anda

5. **Run migrations and seeders:**
php artisan migrate --seed
php artisan db:seed --class=CategorySeeder

6. **Start development server:**
php artisan serve


### Default Login
Email: admin@ronadamar.com
Password: admin123

### Usage
**Web Interface**
Access http://localhost:8000 for the web admin
Navigate to different sections via sidebar

**API Testing**
Access http://localhost:8000/api-tester for API testing interface
Use the interactive tester to test all endpoints



<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
