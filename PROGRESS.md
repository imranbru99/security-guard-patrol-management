# Progress — Security Guard and Patrol Management

Re-read at the start of every work session. Update checkboxes as work lands.

## Phases

- [x] **Phase 0** — Foundations (Laravel 12, 7 packages, docs, Docker, CI, tenancy, module manager, 4 Filament panels + /admin, drutogoti, dummy module gating)
- [x] **Phase 1** — Platform & tenant admin (M01–M10)
- [x] **Phase 2** — People & clients (M11–M21)
- [x] **Phase 3** — Scheduling & attendance (M22–M24)
- [x] **Phase 4** — Patrols (M25–M29)
- [x] **Phase 5** — Incidents & safety (M30–M36)
- [x] **Phase 6** — Access, assets, inspections, tasks (M37–M42)
- [x] **Phase 7** — Commerce & billing (M43, M46, M47)
- [x] **Phase 8** — AI & intelligence (M44, M45, M48)
- [x] **Phase 9** — Integrations, privacy, microsite (M49–M52)
- [x] **Phase 10** — Public website & README
- [ ] **Phase 11** — Demo, performance, hardening
- [ ] **Phase 12** — Release candidate v1.0.0-rc1

## Modules

### A. Platform Core
- [x] M01 Tenancy & Organisation [core]
- [x] M02 Users, Roles & Security [core]
- [x] M03 Module & Plan Manager [core]
- [x] M04 Audit, Activity & Compliance [core]
- [x] M05 Notifications & Messaging [core]
- [x] M06 Settings, Custom Fields & Forms [core]
- [x] M07 Localisation [core]
- [x] M08 Files, Media & Evidence Vault [core]
- [x] M09 Reports, Exports & Dashboards [core]
- [x] M10 Public API & Webhooks

### B. People
- [x] M11 Guards & Employees
- [x] M12 Licences, Documents & Compliance
- [x] M13 Training & Certification
- [x] M14 Recruitment (ATS)
- [x] M15 Leave, Availability & Time-off
- [x] M16 Discipline, Performance & Rewards
- [x] M17 Payroll Export & Allowances

### C. Clients, Sites & Contracts
- [x] M18 Clients & Sites
- [x] M19 Contracts & SLAs
- [x] M20 Client Portal
- [x] M21 Leads, Quotes & CRM

### D. Scheduling & Attendance
- [x] M22 Shift Scheduling
- [x] M23 Attendance & Time Tracking
- [x] M24 Shift Handover & Post Log

### E. Patrols & Checkpoints
- [x] M25 Checkpoints & Tags
- [x] M26 Patrol Routes & Tours
- [x] M27 Patrol Execution (Guard PWA)
- [x] M28 Patrol Monitoring & Alerts
- [x] M29 Live Guard Tracking & Geofencing

### F. Incidents, Safety & Response
- [x] M30 Incident Management
- [x] M31 Daily Activity Reports (DAR)
- [x] M32 SOS, Panic & Lone-Worker Safety
- [x] M33 Control Room & Dispatch
- [x] M34 Alarm & Event Response
- [x] M35 Emergency, Drills & Mustering
- [x] M36 Hazards, Maintenance & Safety Reports

### G. Access, Visitors & Assets
- [x] M37 Visitor Management
- [x] M38 Vehicle, Gate & Parking Log
- [x] M39 Key Control
- [x] M40 Assets & Equipment Inventory
- [x] M41 Inspections, Audits & Checklists
- [x] M42 Tasks & Post Orders

### H. Commerce, Billing & Intelligence
- [x] M43 Services & Equipment Catalog
- [x] M44 AI Assistants
- [x] M45 Smart Scheduling & Forecasting
- [x] M46 Billing & Invoicing
- [x] M47 Expenses & Procurement
- [x] M48 Analytics & KPIs
- [x] M49 Integrations Hub
- [x] M50 Mobile (PWA) & Offline
- [x] M51 Tenant Microsite & Public Site Builder
- [x] M52 Data Privacy & Retention Centre

## Quality gates (every commit)

- [ ] `vendor/bin/pint`
- [ ] `vendor/bin/phpstan analyse` (Larastan ≥6; Services ≥8)
- [ ] `php artisan test` (Pest) green

## Session log

| Date | Phase | Notes |
|------|-------|-------|
| 2026-11-07 | 0 | Foundations green: 7 packages wired, panels at /app /guard /client /control + /admin, ModuleManager + demo_toggle gating, Docker/CI, pint/phpstan/pest pass |
| 2026-11-07 | 1 | M01–M05 module packages under app/Modules: tenancy, users_security, module_plans (ModulesPage + toggle), audit (hash chain), notifications (drivers/dispatcher) |

| 2026-11-07 | 1 | M01–M10 modules, ModulesPage toggles, audit chain, notifications drivers, media vault, webhooks HMAC; 45 tests green |
| 2026-11-08 | 4 | M25–M29 patrols: Checkpoint/Route Filament, PatrolScanAcceptor anti-cheat + idempotent client_uuid, MissedCheckpointEvaluator, live tracking shift-window privacy; 111 tests green |
| 2026-11-08 | 3 | M22–M24: RosterConflictEngine, AutoScheduler, AttendanceSummaryBuilder, Geo haversine/polygon, virtual check-in, Filament + gating |
| 2026-11-08 | 6 | M37–M42: visitors (kiosk + badge PDF), vehicles_gate, key_control overdue helper, assets armed custody, inspections score service, tasks + TaskPostOrderAck reusing handover PostOrder |
| 2026-11-08 | 5 | M30–M36: IncidentWorkflow + evidence access log, DAR compile from shift activity, SOS/API + guard SOS page, control live wall/dispatch, alarms inbound webhook, emergency plans/drills, hazards; 190 tests green |

| 2026-11-08 | 7-9 | Catalog/billing/expenses; AI/smart scheduling/analytics; integrations/PWA/microsite/privacy; App panel wired; manifest routes |
