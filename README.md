# Beautify Services

Beautify_Homeservices_Aliza is a home beauty-service booking application.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `admins`, `bookings`, `booking_services`, `faqs`, `services`, `service_categories`, `stories`, `users`.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Root Pages | [`about.php`](about.php), [`contact_us.php`](contact_us.php), [`faqs.php`](faqs.php), [`login.php`](login.php), [`register.php`](register.php) |
| Admin | [`admin/admin_bookings.php`](admin/admin_bookings.php), [`admin/admin_home.php`](admin/admin_home.php), [`admin/admin_login.php`](admin/admin_login.php), [`admin/admin_navbar.php`](admin/admin_navbar.php), [`admin/categories_list.php`](admin/categories_list.php) |
| User | [`user/book_service.php`](user/book_service.php), [`user/dashboard.php`](user/dashboard.php), [`user/service_categories.php`](user/service_categories.php), [`user/services.php`](user/services.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/Beautify_Homeservices_Aliza.git
cd Beautify_Homeservices_Aliza
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`Database/beautify_aliza_db.sql`](Database/beautify_aliza_db.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`config.php`](config.php), [`user/config.php`](user/config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`Database`](Database) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`css`](css) | Stylesheets |
| [`images`](images) | Image assets |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
