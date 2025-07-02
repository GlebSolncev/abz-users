# User Management API

A simple Laravel 12 + Vue 3 application for managing users. Provides endpoints to list positions, list and create users, and issue temporary JWT tokens. Images are processed via TinyPNG API.

## Tech Stack

### Backend
- **Framework:** Laravel 12 (PHP 8.1+(work by 8.3))
- **Routing & Middleware:** HTTP API routes in `routes/api.php`
- **Authentication:** Custom JWT (`firebase/php-jwt`)
- **Image Processing:** TinyPNG API client (`tinify/tinify`)
- **Storage:** Laravel Filesystem (`public` disk)
- **Database:** MySQL
- **Testing:** PHPUnit, Mockery

### Frontend
- **Framework:** Vue 3
- **Bundler:** Vite
- **Styling:** Tailwind CSS
- **HTTP:** Fetch API
- **Build Tools:** npm, ESLint

## Third‑Party Libraries

### Composer
- **firebase/php-jwt** — encode/decode JWT tokens
- **tinify/tinify** — TinyPNG image optimization client

## Installation

### 1. Clone & Dependencies

```bash
git clone https://github.com/your-org/your-repo.git
cd your-repo
```
# Backend dependencies
```bash
composer install
```
# Copy env
```bash
cp .env.example .env
```
# DB migrate
```bash
php artisan migrate
```
# Key generate
```bash
php artisan key:generate
```
# Serve
```bash
php artisan serve
```


