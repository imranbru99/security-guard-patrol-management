<p align="center">
  <img src="docs/screenshots/dashboard.svg" alt="Security Guard Patrol Management preview" width="100%"/>
</p>

# Security Guard Patrol Management

<p align="center"><strong>Shifts, checkpoints, incidents and patrol proofs</strong></p>

<p align="center">
  <a href="https://github.com/imranbru99/security-guard-patrol-management"><img alt="GitHub" src="https://img.shields.io/badge/GitHub-imranbru99%2Fsecurity-guard-patrol-management-0F172A?style=for-the-badge&logo=github"/></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-PHP-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Author" src="https://img.shields.io/badge/Author-Imran%20Ahmed-14B8A6?style=for-the-badge"/>
</p>

---

## Overview

**Security Guard Patrol Management** — Shifts, checkpoints, incidents and patrol proofs.

This README documents the **real application features** so GitHub and Google visitors can understand what the product does.

## Product screenshots

| Dashboard | Operations |
| :---: | :---: |
| ![Dashboard](docs/screenshots/dashboard.svg) | ![Operations](docs/screenshots/operations.svg) |

## Application features

### Security guard & patrol

- Guard roster, shifts and post assignments
- Checkpoint / NFC-QR patrol scanning
- Live attendance and missing-patrol alerts
- Incident reporting with evidence attachments
- Client sites, posts and SLA tracking
- Supervisor dashboards and exception queues
- Payroll-ready hours and overtime logs
- Client portal for incident and patrol proofs

### Detailed modules from product specification

- **imrandevbd/drutogoti — speed and performance profiling**
- **imrandevbd/laravel-admin-panel — platform owner dashboard**
- **imrandevbd/laravel-ai-catalog — Services & Equipment Catalog (scraping and AI listing generation)**
- **imrandevbd/laravel-ai-hub — every AI feature goes through this**
- **imrandevbd/laravel-attendance-hub — any attendance machine**
- **imrandevbd/laravel-unicode-pdf — PDFs in any language**
#### imrandevbd/laravel-universal-slug — slugs in any language

- ARCHITECTURE — MODULAR, MULTI-TENANT, TOGGLEABLE

- **Folder layout**
- **Module manifest (module.php) — required keys**
- **Module manager (core service)**
- **Business presets (apply with one click, fully editable afterwards)**
#### Tenancy details

- USERS, ROLES AND PANELS
- MODULE CATALOGUE — BUILD EVERYTHING BELOW
- DOMAIN MODEL (minimum; extend as needed — every tenant table has tenant_id, soft deletes where sensible, ULID or UUID public IDs, indexes on tenant_id + hot columns)
- CROSS-CUTTING SYSTEMS

- **Rule engines & algorithms (deterministic, unit-tested)**
- **Notifications**
- **Search**
- **Reporting & documents**
- **Realtime**
- **Guard PWA & offline**
- **Public API**
- **Internationalisation**
- **Security, privacy & compliance**
#### Accessibility & UX

- PUBLIC WEBSITE (many public pages) AND README

- **Public pages — all must exist, be responsive, fast, multilingual, SEO-complete**
#### README.md (required, comprehensive, generated in part by php artisan docs:sync-readme)

- DEMO ENVIRONMENT (must work with php artisan demo:install)
- DELIVERY PHASES (do in order; commit after each; update PROGRESS.md)
- HARD DON'TS
- FIRST ACTIONS (do these now, in this order)

### Implemented application modules

- Ai Assistants
- Alarms
- Analytics
- Assets
- Attendance Tracking
- Audit Compliance
- Billing
- Catalog
- Checkpoints Tags
- Client Portal Shell
- Clients Sites
- Contracts Slas
- Control Room
- Dar
- Demo Toggle
- Discipline Performance
- Emergency
- Expenses
- Guards Employees
- Hazards
- Incidents
- Inspections
- Integrations
- Key Control
- Leads Crm
- Leave Availability
- Licences Documents
- Live Tracking
- Localisation
- Media Vault
- Microsite
- Mobile Pwa
- Module Plans
- Notifications Messaging
- Patrol Execution
- Patrol Monitoring
- Patrol Routes
- Payroll Export
- Privacy
- Public Api Webhooks
- Recruitment Ats
- Reports Dashboards
- Settings Forms
- Shift Handover
- Shift Scheduling
- Smart Scheduling
- Sos
- Tasks Post Orders
- Tenancy Organisation
- Training Certification
- Users Security
- Vehicles Gate
- Visitors

## Tech stack

- PHP 8.2+ / Laravel
- Filament admin (where enabled)
- Migrations, seeders, queues
- Vite asset pipeline
- MySQL / SQLite ready

## Quick start

```bash
git clone https://github.com/imranbru99/security-guard-patrol-management.git
cd security-guard-patrol-management
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Author

**Imran Ahmed** · https://imrandev.bd · me@imrandev.bd  
GitHub: https://github.com/imranbru99/security-guard-patrol-management

---
## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, AI Platform Architecture Contracts, and Enterprise Technical Advisory.

| Channel | Link |
| :--- | :--- |
| Portfolio | https://imrandev.bd |
| GitHub | https://github.com/imranbru99 |
| LinkedIn | https://linkedin.com/in/imranbru99 |
| Email | me@imrandev.bd |
| WhatsApp | https://wa.me/8801576918420 |
| Packagist | https://packagist.org/packages/imrandevbd/ |

