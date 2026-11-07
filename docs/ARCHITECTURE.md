# Architecture — Security Guard and Patrol Management

Product: **Security Guard and Patrol Management: shifts, checkpoints, incidents**  
Stack: Laravel 12 · PHP 8.3+ · Filament v3 · MySQL 8 · Redis · Reverb

## Folder map

```
app/
  Core/                     # Tenancy, ModuleManager, permissions glue, helpers
    Tenancy/
    Modules/
    Permissions/
    Concerns/
    Http/Middleware/
    Support/
  Modules/<ModuleName>/     # Self-contained module packages
    Models, Policies, Services, Actions, Jobs, Events
    Filament/{Resources,Pages,Widgets}
    Http/{Controllers,Requests}
    Api/
    Database/{migrations,seeders,factories}
    Lang, Views, routes.php, module.php
  Panels/                   # Filament panel providers (NOT /admin)
    AppPanelProvider.php      → /app
    GuardPanelProvider.php    → /guard
    ClientPanelProvider.php   → /client
    ControlPanelProvider.php  → /control
  Platform/                 # laravel-admin-panel resources at /admin
resources/views/public/     # Marketing site (Blade + Tailwind + Alpine/Livewire)
docs/                       # Living architecture & package notes
```

## Locked technical decisions (Section 2)

| Area | Decision |
|------|----------|
| Language/runtime | PHP 8.3+, `declare(strict_types=1)` everywhere |
| Framework | Laravel 12 (attendance-hub caps at 12) |
| Company/guard/client/control UI | Filament v3.3.x + Livewire 3 |
| Platform owner console | `imrandevbd/laravel-admin-panel` at `/admin` only |
| Filament paths | `/app`, `/guard`, `/client`, `/control` — never `/admin` |
| Database | MySQL 8 primary; SQLite/PostgreSQL-portable migrations for tests |
| Cache/queue/locks | Redis; `sync` queue in tests |
| Auth | Session + Sanctum API; 2FA TOTP for staff |
| RBAC | spatie/laravel-permission + filament-shield; tenant-scoped |
| Tenancy | Single DB, `tenant_id` + `BelongsToTenant` global scope |
| Public frontend | Blade + Tailwind + Alpine/Livewire + Vite |
| Maps | Leaflet + OSM default; Google Maps optional via env |
| Realtime | Laravel Reverb + polling fallback |
| PDFs | `imrandevbd/laravel-unicode-pdf` |
| Slugs | `imrandevbd/laravel-universal-slug` |
| Attendance devices | `imrandevbd/laravel-attendance-hub` |
| AI | All via `imrandevbd/laravel-ai-hub`; catalog via `laravel-ai-catalog` |
| Perf | `imrandevbd/drutogoti` |
| Testing | Pest + Larastan (≥6, ≥8 on Services) + Pint + Playwright |

## Module catalogue (M01–M52)

See `docs/MODULES.md` for the live registry. Summary groups:

- **A. Platform Core** — M01–M10 (M01–M09 core; M10 Public API)
- **B. People** — M11–M17
- **C. Clients, Sites & Contracts** — M18–M21
- **D. Scheduling & Attendance** — M22–M24
- **E. Patrols & Checkpoints** — M25–M29
- **F. Incidents, Safety & Response** — M30–M36
- **G. Access, Visitors & Assets** — M37–M42
- **H. Commerce, Billing & Intelligence** — M43–M52

## Decisions log

| Date | Decision | Reason |
|------|----------|--------|
| 2026-11-07 | PHP constraint `^8.3` (runtime may be 8.4/8.5) | Prompt locks 8.3; packages support 8.3+ |
| 2026-11-07 | ULID public IDs on domain models; bigint internal PKs | Portable, sortable public IDs |
| 2026-11-07 | Money as integer minor units + ISO currency | Avoid float rounding |
| 2026-11-07 | Module OFF never deletes data | Re-enable restores menus/routes/jobs |
| 2026-11-07 | Demo AI/SMS/payments use fake drivers without keys | App runnable without secrets |
| 2026-11-07 | Payroll is export-ready, not statutory | Explicit product boundary |
| 2026-11-07 | Dummy module `demo_toggle` in Phase 0 for gating proof | Acceptance before real modules |

## Naming rules

- Module folder: PascalCase matching id without prefix (`Patrols` for `patrols`)
- Module id: snake_case (`patrols`, `incident_management`)
- Permissions: `{module}.{resource}.{action}` e.g. `patrols.tours.view`
- Tables: snake_plural; always `tenant_id` on tenant data
- Services: `{Verb}{Noun}Service` or domain nouns (`RosterConflictEngine`)
- Never edit `vendor/`; wrap/extend packages

## Enforcement points for module toggles

1. Route middleware `module:{id}`
2. Filament `HasModuleGate` on resources/pages/widgets
3. API middleware
4. Blade `@module` / `@endmodule`
5. Policy short-circuit
6. Scheduled job tenant skip
7. Notification channel guard
8. Client portal / microsite section guard

## Package wiring

See `docs/PACKAGE_NOTES.md` (namespaces verified from each package’s `composer.json` / README).
Gaps/bugs → `docs/PACKAGE_FEEDBACK.md`.
