# Module Registry

Single source of truth. Synced to DB via `php artisan modules:sync`.  
Public Features/Pricing, plan builder, navigation, permissions, and demo seeders are generated from module manifests under `app/Modules/*/module.php`.

## Registry status

| ID | Module | Group | Plan tier | Status |
|----|--------|-------|-----------|--------|
| tenancy | Tenancy & Organisation | Platform Core | core | Phase 1 in progress |
| users_security | Users, Roles & Security | Platform Core | core | Phase 1 in progress |
| module_plans | Module & Plan Manager | Platform Core | core | Phase 1 in progress |
| audit | Audit, Activity & Compliance | Platform Core | core | Phase 1 in progress |
| notifications | Notifications & Messaging | Platform Core | core | Phase 1 in progress |
| settings_forms | Settings, Custom Fields & Forms | Platform Core | core | Phase 1 |
| localisation | Localisation | Platform Core | core | Phase 0/1 |
| media_vault | Files, Media & Evidence Vault | Platform Core | core | Phase 1 |
| reports | Reports, Exports & Dashboards | Platform Core | core | Phase 1 |
| public_api | Public API & Webhooks | Platform Core | starter | Phase 1 |
| guards | Guards & Employees | People | starter | Phase 2 |
| licences | Licences, Documents & Compliance | People | starter | Phase 2 |
| training | Training & Certification | People | pro | Phase 2 |
| recruitment | Recruitment (ATS) | People | pro | Phase 2 |
| leave | Leave, Availability & Time-off | People | starter | Phase 2 |
| discipline | Discipline, Performance & Rewards | People | pro | Phase 2 |
| payroll_export | Payroll Export & Allowances | People | pro | Phase 2 |
| clients_sites | Clients & Sites | Clients | starter | Phase 2 built |
| contracts_slas | Contracts & SLAs | Clients | starter | Phase 2 built |
| client_portal | Client Portal | Clients | starter | Phase 2 built |
| leads_crm | Leads, Quotes & CRM | Clients | starter | Phase 2 built |
| shift_scheduling | Shift Scheduling | Scheduling | starter | Phase 3 |
| attendance | Attendance & Time Tracking | Scheduling | starter | Phase 3 |
| handover | Shift Handover & Post Log | Scheduling | starter | Phase 3 |
| checkpoints | Checkpoints & Tags | Patrols | starter | Phase 4 |
| patrol_routes | Patrol Routes & Tours | Patrols | starter | Phase 4 |
| patrol_execution | Patrol Execution | Patrols | starter | Phase 4 |
| patrol_monitoring | Patrol Monitoring & Alerts | Patrols | pro | Phase 4 |
| live_tracking | Live Guard Tracking & Geofencing | Patrols | pro | Phase 4 |
| incidents | Incident Management | Safety | starter | Phase 5 |
| dar | Daily Activity Reports | Safety | starter | Phase 5 |
| sos | SOS, Panic & Lone-Worker Safety | Safety | pro | Phase 5 |
| control_room | Control Room & Dispatch | Safety | pro | Phase 5 |
| alarms | Alarm & Event Response | Safety | enterprise | Phase 5 |
| emergency | Emergency, Drills & Mustering | Safety | enterprise | Phase 5 |
| hazards | Hazards, Maintenance & Safety | Safety | pro | Phase 5 |
| visitors | Visitor Management | Access | starter | Phase 6 |
| vehicles_gate | Vehicle, Gate & Parking Log | Access | starter | Phase 6 |
| key_control | Key Control | Access | starter | Phase 6 |
| assets | Assets & Equipment Inventory | Access | pro | Phase 6 |
| inspections | Inspections, Audits & Checklists | Access | pro | Phase 6 |
| tasks_post_orders | Tasks & Post Orders | Access | starter | Phase 6 |
| catalog | Services & Equipment Catalog | Commerce | pro | Phase 7 |
| ai_assistants | AI Assistants | Commerce | addon | Phase 8 |
| smart_scheduling | Smart Scheduling & Forecasting | Commerce | addon | Phase 8 |
| billing | Billing & Invoicing | Commerce | pro | Phase 7 |
| expenses | Expenses & Procurement | Commerce | pro | Phase 7 |
| analytics | Analytics & KPIs | Commerce | pro | Phase 8 |
| integrations | Integrations Hub | Commerce | pro | Phase 9 |
| mobile_pwa | Mobile (PWA) & Offline | Commerce | starter | Phase 9 |
| microsite | Tenant Microsite & Public Site Builder | Commerce | starter | Phase 9 |
| privacy | Data Privacy & Retention Centre | Commerce | core | Phase 9 |
| demo_toggle | Demo Toggle (Phase 0 proof) | Platform Core | core | Phase 0 |

## Manifest required keys

`id`, `name`, `group`, `icon`, `description`, `features[]`, `depends_on[]`, `conflicts_with[]`, `permissions[]`, `filament_resources[]`, `api_scopes[]`, `scheduled_jobs[]`, `widgets[]`, `settings_schema[]`, `default_on_presets[]`, `plan_tier`, `price`, `public_page`, `demo_seeder`.

## Business presets

| Preset | Default modules |
|--------|-----------------|
| small_guarding | shifts, attendance, patrols, incidents, dar (+ cores) |
| residential_society | visitors, gate, parking, complaints→hazards, patrol, sos |
| corporate_campus | visitors, access, key_control, assets, inspections |
| mall_retail | patrols, incidents, lost_found→hazards, crowd→tasks, cctv→alarms |
| industrial | vehicles_gate, material passes, hazards, emergency, assets |
| event_security | open shifts, bids, headcount, incidents, radio, temp staff |
| cash_in_transit | vehicle patrol, trips, panic, geofence, armed assets |
| remote_monitoring | check-in timers, sos, alarms, dispatch |
| enterprise | everything |

Presets are applied via ModuleManager and remain editable afterwards.
