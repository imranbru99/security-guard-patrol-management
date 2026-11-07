# Package Notes — imrandevbd/*

Namespaces verified from each package’s `composer.json` autoload map (Phase 0).

## Namespace map (important — inconsistent across packages)

| Package | Namespace | Provider |
|---------|-----------|----------|
| drutogoti | `Drutogoti\` | `Drutogoti\DrutogotiServiceProvider` |
| laravel-admin-panel | `ImranDevBD\LaravelAdminPanel\` | `ImranDevBD\LaravelAdminPanel\AdminPanelServiceProvider` |
| laravel-ai-hub | `ImranDevBd\AiHub\` | `ImranDevBd\AiHub\AiHubServiceProvider` |
| laravel-ai-catalog | `ImranDevBd\AiCatalog\` | `ImranDevBd\AiCatalog\AiCatalogServiceProvider` |
| laravel-attendance-hub | `ImranDevBd\AttendanceHub\` | *(auto-discovered)* |
| laravel-unicode-pdf | `ImranDev\UnicodePdf\` | `ImranDev\UnicodePdf\UnicodePdfServiceProvider` |
| laravel-universal-slug | `ImranDev\UniversalSlug\` | `ImranDev\UniversalSlug\UniversalSlugServiceProvider` |

Note casing: `ImranDevBD` (admin-panel) vs `ImranDevBd` (ai-hub/catalog/attendance) vs `ImranDev` (pdf/slug).

---

## 1. imrandevbd/drutogoti (`dev-main`)

- Facade/API: see `Drutogoti\` under `src/`
- Publish: `php artisan vendor:publish --tag=drutogoti-config` / `--tag=drutogoti-migrations`
- Dashboard: `/drutogoti` — gate to platform super-admins
- Env: `DRUTOGOTI_STORAGE_DRIVER=queue`, `DRUTOGOTI_SAMPLE_RATE=1.0` (local) / `0.1` (prod)
- Exclude hot paths: `/up`, `/reverb*`, `/api/v1/patrol/scan`

**App usage:** performance watchdog; platform console Performance card.

## 2. imrandevbd/laravel-admin-panel (`dev-main`)

- Install: `php artisan admin-panel:install`
- Mount: `/admin` **only** (never Filament)
- Fluent Resource API for Tenants, Plans, Modules, etc.
- Built-ins: audit log, health, log viewer, jobs, media, webhooks, RBAC, impersonation, passkeys, 8 palettes, command palette

**App usage:** platform owner console (`app/Platform/`).

## 3. imrandevbd/laravel-ai-hub (`^1.6` → v1.7.3)

- Facade: `ImranDevBd\AiHub\Facades\AIHub` (verify after publish)
- Studio gated via `AIHub::auth()` to platform admins
- Demo: `AIHub::fake()` canned responses
- Tag jobs: `->forJob("tenant:{id}:feature")` for budgets

**App usage:** all AI features (M44); never call vendor SDKs from feature code.

## 4. imrandevbd/laravel-ai-catalog (`dev-main`)

- Namespace: `ImranDevBd\AiCatalog\`
- Depends on ai-hub
- Studio `/ai-catalog` platform-only; public `llms.txt`
- Disable e-commerce-only 2027 features unless useful; keep compliance packs & budgets

**App usage:** M43 Services & Equipment Catalog.

## 5. imrandevbd/laravel-attendance-hub (`dev-main`)

- Namespace: `ImranDevBd\AttendanceHub\`
- Tenancy: `ATTENDANCE_TENANCY_ENABLED=true` + tenant resolver
- Filament v3 plugin; `AttendanceHub::fake()` / `attendance:simulate`
- ADMS `/iclock/cdata`; document sockets requirement for ZKTeco LAN

**App usage:** M23 Attendance; device manager; virtual GPS/QR for demo.

## 6. imrandevbd/laravel-unicode-pdf (`dev-main`)

- Facade: `ImranDev\UnicodePdf\Facades\UnicodePdf`
- Fonts: `php artisan unicode-pdf:font:install --font=universal` (+ bengali, arabic, devanagari)
- Models: `Pdfable` / `GeneratesPdf`; tests: `UnicodePdf::fake()`

**App usage:** every printable document.

## 7. imrandevbd/laravel-universal-slug (`dev-main`)

- Trait path (verified from vendor autoload/`src/`): `ImranDev\UniversalSlug\Traits\HasUniversalSlug` (and `HasSlugHistory` in the same `Traits` namespace). Older notes saying `Concerns\HasUniversalSlug` are **wrong** — that namespace does not exist in the package.
- Models must implement `getSlugOptions(): SlugOptions` (or use `#[Slug]`) — see Site model in M18 `ClientsSites`.
- Multi-tenant uniqueness: `->uniqueScope(fn ($q, $m) => $q->where('tenant_id', $m->tenant_id))`.
- Prefer keeping Filament/API route keys on `ulid` (override `getRouteKeyName()` on the model) rather than enabling `useAsRouteKey` on slug options for Phase 2.
- Extend reserved words: app, guard, client, control, admin, demo, pricing, features, …
- Schedule: `slug:prune` daily, `slug:audit` weekly

**App usage:** Site (M18) uses `HasUniversalSlug`; other public/URL models should follow.

---

## Related non-imrandevbd packages

| Package | Role |
|---------|------|
| filament/filament ^3.3 | `/app` `/guard` `/client` `/control` |
| bezhansalleh/filament-shield | permission UI for Filament |
| spatie/laravel-permission | RBAC |
| laravel/sanctum | API tokens |
| laravel/reverb | WebSockets |
