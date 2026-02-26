# WP + Astro Starter

Headless WordPress + Astro SSR starter kit. WordPress serves as the CMS backend (ACF blocks, menus, options), Astro handles the frontend with server-side rendering and Vercel ISR.

## Architecture

```
WordPress (Docker)          Astro SSR (Vercel)
┌─────────────────┐        ┌──────────────────────┐
│ ACF Blocks      │        │ [...slug].astro       │
│ Menus           │──REST──│ ContentRenderer.astro │
│ Options (ACF)   │  API   │ Components (Hero,     │
│ CF7 Forms       │        │   About, Services...) │
│ Rank Math SEO   │        │ ISR + Revalidation    │
└─────────────────┘        └──────────────────────┘
```

## Stack

**WordPress:**
- Theme: `foxo` (custom headless theme)
- ACF Pro (Flexible Content blocks)
- Contact Form 7
- Rank Math SEO

**Astro:**
- SSR with `@astrojs/vercel` adapter
- ISR (Incremental Static Regeneration) with on-demand revalidation
- Tailwind CSS v4
- TypeScript

## Quick Start

### 1. Start WordPress (Docker)

```bash
docker-compose up -d
```

- WordPress: http://localhost:8080
- WP Admin: http://localhost:8080/wp-admin/ (`admin` / `password`)
- PHPMyAdmin: http://localhost:8000 (`root` / `root`)

### 2. Start Astro dev server

```bash
cd astro-foxo
cp .env.example .env
npm install
npm run dev
```

- Frontend: http://localhost:4321

## WordPress Theme (foxo)

Located in `public_html/wp-content/themes/foxo/`.

### REST API Endpoints (`foxo/v1`)

| Endpoint | Description |
|---|---|
| `GET /page/{slug}` | Page with parsed ACF blocks (supports hierarchical paths) |
| `GET /menu/{location}` | Menu items (`main_menu`, `footer_menu`) |
| `GET /options` | Site options (logo, contact, social, copyright) |

### ACF Blocks

| Block | Fields |
|---|---|
| `foxo-hero` | Heading, description, CTAs, YouTube URL |
| `foxo-about` | Heading, description, features repeater |
| `foxo-services` | Heading, services repeater with features |
| `foxo-references` | Clients repeater, testimonials repeater |
| `foxo-contact` | Info cards, CF7 form integration |

### Headless Features

- **Redirect**: WP frontend redirects to Astro (except admin, REST, sitemaps)
- **Preview**: WP preview links open Astro preview page with token auth
- **Revalidation**: Hooks on save_post, menu change, customizer, ACF options
- **SEO**: Rank Math head tags proxied via `getSeoHead()`
- **Sitemaps**: Rank Math XML sitemaps proxied through Astro endpoints

## Astro Frontend (astro-foxo)

### Key Files

| File | Purpose |
|---|---|
| `src/pages/[...slug].astro` | Catch-all SSR route |
| `src/pages/preview.astro` | WP preview page |
| `src/pages/api/revalidate.ts` | On-demand ISR revalidation + Vercel Purge API |
| `src/components/ContentRenderer.astro` | Maps ACF blocks to Astro components |
| `src/lib/wp-api.ts` | WP REST API client |
| `src/lib/types.ts` | TypeScript interfaces |

### Components

Hero, About, Services, References, Testimonials, Contact, Header, Footer, AdminBar

### Environment Variables

See `astro-foxo/.env.example` for all required variables.

For Vercel deployment, also set:
- `VERCEL_REVALIDATE_TOKEN` — Vercel API token for ISR purge
- `VERCEL_PROJECT_ID` — Vercel project ID
- `VERCEL_TEAM_ID` — Vercel team ID (optional)

## Docker Commands

```bash
docker-compose up -d      # Start containers (background)
docker-compose stop        # Stop containers
docker-compose down        # Stop and remove containers
docker-compose ps          # List containers
```
