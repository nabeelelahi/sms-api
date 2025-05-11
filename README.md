
---

### ✅ **Laravel Backend – `README.md`**

```markdown
# School Management API – Laravel Backend

This is the backend API for the School Management App built using Laravel and PHP.

## 🔧 Tech Stack

- Laravel 12+
- Sanctum for token-based authentication
- MySQL or SQLite (any SQL DB works)
- Laravel Seeder & Migration
- RESTful API principles

## 🚀 Setup Instructions

1. Clone the repo
2. Run the following commands:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
