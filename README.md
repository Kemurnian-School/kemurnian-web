# Kemurnian Web

Kemurnian Web is a Laravel 13 + Inertia React application for Sekolah Kemurnian. It includes a public site (guest pages) and an admin dashboard for managing content.

## Available Pages

Public:
- `/` Home (hero slider, schools info, about, curriculum, news, enrollment, contact)
- `/about`
- `/news`
- `/news/category/{slug}`
- `/news-detail/{id}`
- `/enrollment`
- `/kurikulum/{id}`
- `/unit/{detail}`
- `/{sekolah}` (sekolah-kemurnian-1/2/3)

Admin (requires login):
- `/admin` Dashboard
- `/admin/hero`
- `/admin/kurikulum`
- `/admin/news`
- `/admin/enrollment`
- `/admin/fasilitas`

Auth:
- `/login` (admin login)

## Project Structure

Key folders:
- `app/Http/Controllers/Admin` Admin CRUD controllers
- `app/Http/Controllers/Guest` Public site controller
- `app/Http/Controllers/Auth` Login controller
- `app/Http/Middleware` Admin guard
- `database/migrations` Schema and content tables
- `resources/js/Pages` Inertia pages
- `resources/js/Components` Shared UI components
- `resources/js/Layouts` Layout wrappers
- `resources/js/data` JSON data (schools)
- `resources/css` Tailwind and theme variables
- `routes/web.php` Public + admin routes
- `routes/auth.php` Login/logout routes

## How The App Works

High-level flow:
- `routes/web.php` maps URLs to controllers.
- Public routes use `Guest/SiteController` and render Inertia pages in `resources/js/Pages/Guest`.
- Admin routes are protected by `auth` + `admin` middleware and render pages in `resources/js/Pages/Admin`.
- Image uploads are stored in `public/uploads` and mapped to public URLs.
- Schools info for `/unit/{detail}` and `/{sekolah}` comes from `resources/js/data/schools.json`.

Data flow:
- Controllers fetch Eloquent models and normalize data (image URLs, dates).
- Inertia passes props to React pages.
- Pages render with `GuestLayout` or `AdminLayout` and shared components.

## Admin Accounts

Create admin users from the CLI:

```bash
php artisan admin:create "Admin Name" admin@example.com "StrongPass123"
```

## Development Guide

### Local Dev With Laravel Sail

1. Start containers:

```bash
./vendor/bin/sail up -d
```

2. Install dependencies and build assets:

```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

3. Run migrations:

```bash
./vendor/bin/sail artisan migrate
```

### Local Dev With Nix

Assuming you already have PHP, Composer, Node, and pnpm/npm via Nix:

1. Install PHP dependencies:

```bash
composer install
```

2. Install JS dependencies:

```bash
pnpm install
# or: npm install
```

3. Run migrations and dev server:

```bash
php artisan migrate
pnpm dev
# or: npm run dev
```

### Production Notes

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- Point your web root to `public/` (or deploy `public/` into `public_html/`).
- Run `php artisan migrate --force` after deploy.
- Build assets with `pnpm build` or `npm run build` and deploy `public/build`.
