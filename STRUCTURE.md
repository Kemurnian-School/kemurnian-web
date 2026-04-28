# Project Structure

This document describes the architecture and file layout of the Kemurnian School website — a Laravel + Inertia.js + React application migrated from Next.js.

---

## Directory Overview

```
kemurnian-web/
├── app/                        # Laravel application code
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/          # Admin panel controllers
│   │   └── Middleware/
│   │       └── HandleInertiaRequests.php  # Shares flash messages globally
│   ├── Models/                 # Eloquent models
│   └── Providers/
├── bootstrap/                  # Laravel bootstrap files
├── config/                     # Laravel config files
├── database/
│   ├── migrations/             # Database schema migrations
│   ├── factories/              # Model factories (for tests)
│   └── seeders/
├── public/                     # Web root (publicly accessible)
│   ├── build/                  # Compiled Vite assets (generated)
│   └── [static assets]         # SVGs, images, audio, robots.txt
├── resources/
│   ├── css/
│   │   └── app.css             # Tailwind CSS v4 entry point + custom theme
│   ├── js/                     # React + TypeScript frontend
│   │   ├── app.tsx             # Inertia.js app entry point
│   │   ├── Components/
│   │   │   └── Admin/          # Reusable admin UI components
│   │   ├── Hooks/              # Custom React hooks
│   │   ├── Layouts/            # Page layout wrappers
│   │   ├── Pages/
│   │   │   └── Admin/          # Inertia page components (one per route)
│   │   └── Utils/              # Pure utility functions
│   └── views/
│       └── app.blade.php       # Single Blade template (Inertia shell)
├── routes/
│   ├── web.php                 # All HTTP routes
│   └── console.php             # Artisan schedule/commands
├── storage/                    # Laravel cache, logs, sessions
├── tests/                      # PHPUnit tests
├── .github/workflows/
│   └── deploy.yml              # CI/CD: build assets → deploy to Hostinger
├── composer.json               # PHP dependencies
├── package.json                # JS dependencies (pnpm)
├── vite.config.js              # Vite bundler config
└── tsconfig.json               # TypeScript config
```

---

## Backend (Laravel)

### Routes — `routes/web.php`

All routes are under the `/admin` prefix. There is no public-facing frontend yet.

```
GET  /admin                    → DashboardController@index
GET  /admin/hero               → HeroController@index
GET  /admin/hero/create        → HeroController@create
POST /admin/hero               → HeroController@store
DELETE /admin/hero/{id}        → HeroController@destroy
POST /admin/hero/reorder       → HeroController@reorder
GET  /admin/kurikulum          → KurikulumController@index
GET  /admin/kurikulum/create   → KurikulumController@create
POST /admin/kurikulum          → KurikulumController@store
GET  /admin/kurikulum/edit/{id}→ KurikulumController@edit
PUT  /admin/kurikulum/{id}     → KurikulumController@update
DELETE /admin/kurikulum/{id}   → KurikulumController@destroy
```

> There is also a temporary `/debug-hero` route used during development.

### Controllers — `app/Http/Controllers/Admin/`

| File | Responsibility |
|---|---|
| `DashboardController.php` | Renders the responsive site-preview dashboard |
| `HeroController.php` | CRUD + drag-and-drop reorder for hero banner images |
| `KurikulumController.php` | CRUD for curriculum (rich text) entries |

All controllers use `Inertia::render('Admin/...')` to return page components to the React frontend.

### Models — `app/Models/`

| Model | Table | Notes |
|---|---|---|
| `Hero` | `heroes` | Has Eloquent accessors that prepend `APP_URL/uploads/` to image paths |
| `Kurikulum` | `kurikulums` | Stores rich-text `body` content |
| `User` | `users` | Standard Laravel auth model (unused so far) |

### Database — `database/migrations/`

| Migration | Table | Key columns |
|---|---|---|
| `0001_01_01_000000` | `users` | Standard Laravel users |
| `0001_01_01_000001` | `cache` | Standard Laravel cache |
| `0001_01_01_000002` | `jobs` | Standard Laravel queue jobs |
| `2026_04_23_023729` | `heroes` | `header_text`, `button_text`, `href`, `desktop_image`, `tablet_image`, `mobile_image`, `order` |
| `2026_04_23_085750` | `kurikulums` | `title`, `body` (longText), `preview`, `order` |

### Middleware — `app/Http/Middleware/HandleInertiaRequests.php`

Shares `flash` data (success/error session messages) with every Inertia response so that the `Snackbar` component can display them globally.

### File Storage — `config/filesystems.php`

A custom disk `public_html` stores hero images **outside the web root** (shared hosting pattern):

```
root: PUBLIC_HTML_PATH env var → defaults to ../public_html/uploads
url:  APP_URL/uploads
```

---

## Frontend (React + Inertia.js)

### Entry Point — `resources/js/app.tsx`

Bootstraps Inertia.js, which auto-resolves page components from `resources/js/Pages/**/*.tsx` and mounts them into the single Blade template (`resources/views/app.blade.php`).

### Pages — `resources/js/Pages/Admin/`

Each file maps 1-to-1 to a Laravel controller action that calls `Inertia::render()`.

```
Pages/Admin/
├── Dashboard.tsx          # Responsive iframe preview of the live site
├── Hero/
│   ├── Index.tsx          # List hero banners
│   └── Create.tsx         # Upload new hero banner (with image compression)
└── Kurikulum/
    ├── Index.tsx          # List curriculum entries
    ├── Create.tsx         # Create new curriculum (ReactQuill rich text editor)
    └── Edit.tsx           # Edit existing curriculum
```

Every page component sets a `layout` property to wrap itself in `AdminLayout`.

### Layouts — `resources/js/Layouts/`

| File | Wraps |
|---|---|
| `AdminLayout.tsx` | All admin pages; renders `<Sidebar>` + `<main>` slot |

### Components — `resources/js/Components/Admin/`

| File | Description |
|---|---|
| `Sidebar.tsx` | Left navigation with links for all admin sections + logout button |
| `ActionButton.tsx` | Red pill-shaped link button (e.g. "+ New Banner") |
| `HeroList.tsx` | Drag-and-drop sortable list of hero banners with delete |
| `KurikulumList.tsx` | List of curriculum entries with edit/delete |
| `ConfirmationModal.tsx` | Hold-to-confirm delete modal with animated progress fill |
| `LoadingProgress.tsx` | Top-of-page progress bar tied to Inertia navigation events |
| `Snackbar.tsx` | Toast notification that reads `flash.success` / `flash.error` from Inertia shared props |
| `HeroUtils/types.ts` | Shared `Hero` TypeScript interface |

### Hooks — `resources/js/Hooks/`

| File | Description |
|---|---|
| `useImageCompression.tsx` | Wraps `compressImageToWebP` with per-device-type loading state and success/error messages |

### Utils — `resources/js/Utils/`

| File | Description |
|---|---|
| `ImageCompression.ts` | Browser `<canvas>`-based image resizing + WebP conversion with configurable quality and max dimensions |

### Styling — `resources/css/app.css`

Tailwind CSS v4 with a custom `@theme` block defining brand colors:

```css
--color-btn-primary: #8b0000
--color-red-primary: #7b1113
--color-btn-hover:   #730202
--color-black-primary: #0e1015
```

Also defines a `bounceIn` keyframe used by `ConfirmationModal` and a custom `.auto-scrollbar` utility.

### Vite Config — `vite.config.js`

Path aliases:

| Alias | Resolves to |
|---|---|
| `@` | `resources/js/` |
| `@AdminComponents` | `resources/js/Components/Admin/` |
| `@GuestComponents` | `resources/js/Components/Guest/` (not yet created) |

---

## What's Implemented vs. Planned

### ✅ Implemented

- Admin dashboard with responsive site iframe preview
- Hero banner management (upload, reorder, delete, WebP compression)
- Kurikulum (curriculum) management (rich text create/edit/delete)
- Flash message snackbar (global)
- Inertia loading progress bar
- GitHub Actions CI/CD pipeline to Hostinger

### 🔲 Planned / Not Yet Done

- **Public-facing frontend** — there are no public routes or guest pages yet; the site currently only serves the admin panel
- **Authentication** — the sidebar logout button and admin routes have no auth guard
- **News** — admin CRUD and public page
- **Enrollment** — admin CRUD and public page
- **Fasilitas** — admin CRUD and public page
- **`@GuestComponents`** — alias defined in Vite but directory doesn't exist yet

---

## CI/CD — `.github/workflows/deploy.yml`

Trigger: push to `main`

Steps:
1. Checkout code
2. Set up Node.js 20 + pnpm
3. `pnpm install && pnpm run build` — produces `public/build/`
4. SCP `public/build/` to the Hostinger server
5. SSH into server → `git pull` → `composer install --no-dev` → `php artisan migrate --force` → cache config/routes/views

Secrets required: `SSH_HOST`, `SSH_USER`, `SSH_PRIVATE_KEY`, `SSH_PORT`
