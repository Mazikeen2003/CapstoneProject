# ProjectTracker

![Laravel](https://img.shields.io/badge/Laravel-EF2D5E?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Composer](https://img.shields.io/badge/Composer-8892BF?style=for-the-badge&logo=composer&logoColor=white)

A modern Laravel-based project management portal designed for local government operations. ProjectTracker brings together planning, monitoring, reporting, and public transparency in one streamlined system.

## ✨ Overview

ProjectTracker is built to support efficient collaboration across departments, city officials, and barangay units. It helps organizations manage projects more effectively while maintaining accountability, visibility, and easy access to reports.

## 🚀 Key Features

- Role-based dashboards for Admin, City Official, Barangay Official, and Department users
- Project creation, review, updates, and edit-permission workflows
- Interactive map views with GeoJSON export for public and authenticated users
- Analytics and budget reporting for performance tracking and compliance
- PDF report generation for projects, budgets, and SGLG summaries
- Audit log access and backup management for secure operations
- Public-facing map and analytics endpoints for transparency

## 👥 User Roles

- **Admin**: Manage users, configure permissions, review audit logs, run backups, and generate system reports
- **City Official**: Review citywide projects, view analytics, and export PDF reports
- **Barangay Official**: Manage local projects, update barangay project data, and monitor barangay analytics
- **Department**: Create and edit departmental projects, request edit permissions, and generate supporting forms
- **Public**: Access public map and analytics pages without authentication

## 🛠️ Tech Stack

- **PHP 8.2**
- **Laravel 12**
- **Tailwind CSS**
- **Vite**
- **Alpine.js**
- **Axios**
- **DOMPDF**
- **Composer**
- **NPM**

## 📦 Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd CapstoneProject
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies:
   ```bash
   npm install
   ```
4. Copy the environment file and generate an application key:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
5. Configure your database connection in `.env`.
6. Run migrations:
   ```bash
   php artisan migrate
   ```

## 💻 Local Development

- Start the application server:
  ```bash
  php artisan serve
  ```
- Start the frontend build watcher:
  ```bash
  npm run dev
  ```

## 🏗️ Production Build

```bash
npm run build
```

## ✅ Testing

Run the test suite with:

```bash
php artisan test
```

## 📁 Project Structure

- `app/Http/Controllers` — application controllers and route handling
- `app/Models` — Eloquent models for projects, users, reports, and more
- `resources/views` — Blade templates and frontend pages
- `routes/web.php` — primary web route definitions and role-based routing
- `database/migrations` — schema migrations for database setup
- `resources/css` and `resources/js` — Tailwind and Vite frontend assets

## 📝 Notes

- Update `.env` with your local database credentials before running migrations.
- Public map and analytics pages are available under `/public/map` and `/public/analytics`.

## 📄 License

This project is released under the **MIT License**.
