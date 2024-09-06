<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About HallMan

...

## Installing HallMan

#### Requirements

- PHP version 8.3
- Composer v2.7.x
- Docker + Compose

#### Steps

1. Open project in `VS Code` or in any other IDE.
2. Copy `.env.example` file to `.env` file.
3. Run `composer install --ignore-platform-reqs` command.
4. Stop running web server (ex: XAMPP) and database server (ex: MySQL)
5. Run `./vendor/bin/sail up -d` command.
6. Run `./vendor/bin/sail php artisan key:generate` command.
7. Run `./vendor/bin/sail php artisan migrate --seed` command.
8. Run `./vendor/bin/sail php artisan storage:link` command.
9. Login at `http://localhost/login`; email: `test@example.com`, password: `password`.

## Use API

1. Visit: `https://localhost/request-docs`
2. Use `/api/students` endpoint to get student records.
3. Use `/api/attendance` endpoint to record `logs`.
