# Kemurnian School Website

Official website for **Sekolah Kemurnian** — migrated from Next.js to **Laravel + Inertia.js + React**.

Live site: [sekolahkemurnian.sch.id](https://sekolahkemurnian.sch.id)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 (PHP 8.3+) |
| Frontend bridge | Inertia.js v3 |
| Frontend UI | React 19 + TypeScript |
| Styling | Tailwind CSS v4 |
| Bundler | Vite 8 |
| Database | SQLite (dev) / MySQL (prod) |
| Package manager | pnpm |
| Deployment | Hostinger shared hosting via GitHub Actions |

---

## Local Development

### Prerequisites

- PHP 8.3+
- Composer
- Node.js 20+ with pnpm

### Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Install JS dependencies and start Vite dev server
pnpm install
pnpm dev
```

Then in a separate terminal:

```bash
php artisan serve
```

Or use the combined dev script:

```bash
composer dev
```

---

## Project Structure

See [STRUCTURE.md](STRUCTURE.md) for a detailed breakdown of the codebase.

---

## Admin Panel

The admin panel lives at `/admin` and manages the following content sections:

| Section | Status | Route |
|---|---|---|
| Dashboard (site preview) | ✅ Done | `/admin` |
| Hero banners | ✅ Done | `/admin/hero` |
| Kurikulum | ✅ Done | `/admin/kurikulum` |
| News | 🔲 Planned | `/admin/news` |
| Enrollment | 🔲 Planned | `/admin/enrollment` |
| Fasilitas | 🔲 Planned | `/admin/fasilitas` |

> **Note:** Authentication/login is not yet implemented. The admin panel is currently unprotected.

---

## Deployment

Deployments to Hostinger are triggered automatically on every push to `main` via GitHub Actions (`.github/workflows/deploy.yml`).

The pipeline:
1. Builds frontend assets with Vite
2. SCPs the built assets to the server
3. SSH-pulls the latest code, runs Composer install, migrations, and caches config/routes/views

---

## File Storage

Hero images are stored outside the web root in a custom `public_html` disk at:

```
~/domains/main.sekolahkemurnian.sch.id/public_html/uploads/
```

This path is configured via the `PUBLIC_HTML_PATH` environment variable (see `config/filesystems.php`).
