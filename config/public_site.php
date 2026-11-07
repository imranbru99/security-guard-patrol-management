<?php

declare(strict_types=1);

/**
 * Static marketing/legal page copy. Feature modules come from ManifestRegistry.
 * Honesty rule: no invented testimonials, awards, or competitor claims.
 */
return [
    'brand' => 'GuardOps',
    'tagline' => 'Security Guard and Patrol Management: shifts, checkpoints, incidents',
    'locales' => ['en', 'bn', 'ar', 'hi', 'es', 'fr'],

    'solutions' => [
        'small-guarding' => [
            'title' => 'Small guarding company',
            'preset' => 'small_guarding',
            'summary' => 'Shifts, attendance, patrols, incidents, and daily activity reports for lean teams.',
        ],
        'residential-society' => [
            'title' => 'Residential society / gated community',
            'preset' => 'residential_society',
            'summary' => 'Visitors, gate logs, patrols, and SOS for residential sites.',
        ],
        'corporate-campus' => [
            'title' => 'Corporate campus / office tower',
            'preset' => 'corporate_campus',
            'summary' => 'Visitors, key control, assets, and inspections for office campuses.',
        ],
        'mall-retail' => [
            'title' => 'Mall / retail',
            'preset' => 'mall_retail',
            'summary' => 'Patrols, incidents, hazards, and crowd-related tasks for retail floors.',
        ],
        'industrial' => [
            'title' => 'Industrial / factory / warehouse',
            'preset' => 'industrial',
            'summary' => 'Vehicle logs, hazards, mustering, and asset control for industrial sites.',
        ],
        'event-security' => [
            'title' => 'Event security',
            'preset' => 'event_security',
            'summary' => 'Open shifts, headcount, incidents, and temporary staffing for events.',
        ],
        'cash-in-transit' => [
            'title' => 'Cash-in-transit / escort',
            'preset' => 'cash_in_transit',
            'summary' => 'Vehicle patrol, geofence, panic, and armed-equipment controls.',
        ],
        'remote-monitoring' => [
            'title' => 'Remote monitoring / lone worker',
            'preset' => 'remote_monitoring',
            'summary' => 'Check-in timers, SOS, alarm response, and dispatch.',
        ],
        'enterprise' => [
            'title' => 'Enterprise multi-branch agency',
            'preset' => 'enterprise',
            'summary' => 'Full module set with branches, SLA, billing, payroll export, AI, and API.',
        ],
    ],

    'pages' => [
        'about' => [
            'title' => 'About GuardOps',
            'body' => 'GuardOps is a modular SaaS for security agencies: roster shifts, prove attendance, verify patrols, capture incidents with evidence, report to clients, and bill for work done. This product is built as open engineering documentation in this repository — not a marketing fiction.',
        ],
        'contact' => [
            'title' => 'Contact',
            'body' => 'Use the quote/demo form for product enquiries. Platform support for paying tenants is handled inside the /admin console tickets module when configured.',
        ],
        'security' => [
            'title' => 'Security & compliance',
            'body' => 'Implemented controls include tenant isolation, module-gated routes, Sanctum API tokens, audit hash chains for evidence custody, encrypted columns for sensitive fields, biometric consent workflows, location tracking limited to shift hours, CSP-ready layouts, and DSAR/erasure jobs. This page describes what is in the codebase — not third-party certifications.',
        ],
        'ai' => [
            'title' => 'AI features',
            'body' => 'All AI calls go through laravel-ai-hub with per-tenant budgets, PII redaction hooks, and draft-until-confirmed outputs. Demo mode uses canned responses when no API keys are configured.',
        ],
        'mobile' => [
            'title' => 'Mobile / PWA',
            'body' => 'The guard panel is installable via /manifest.webmanifest and a service worker. Offline sync accepts idempotent client_uuid payloads for patrol scans and related events.',
        ],
        'integrations' => [
            'title' => 'Integrations',
            'body' => 'Attendance devices via laravel-attendance-hub, ICS shift feeds, inbound alarm webhooks, SMS/WhatsApp drivers (including fake drivers for demo), payment gateway drivers with a fake driver, and HMAC-signed outbound webhooks.',
        ],
        'api' => [
            'title' => 'API & developers',
            'body' => 'REST API under /api/v1 with Sanctum. Health: GET /api/v1/health. Module routes are gated by tenant module toggles. OpenAPI explorer ships when public_api is enabled.',
        ],
        'compare' => [
            'title' => 'Why GuardOps (feature checklist)',
            'body' => 'Compare capabilities using the feature matrix generated from the module registry. We do not publish unverifiable claims about named competitors.',
        ],
        'tour' => [
            'title' => 'Product tour',
            'body' => 'Walk the flow: apply a business preset → add a site and posts → publish a roster → run a patrol tour → file an incident → open the control room → send a client report. Use /demo for one-click role logins once demo data is installed.',
        ],
        'changelog' => [
            'title' => 'Changelog',
            'body' => 'See git history and docs/PROGRESS.md for phase delivery notes. Tagged releases will appear here as they ship.',
        ],
        'roadmap' => [
            'title' => 'Roadmap',
            'body' => 'Near-term: deepen demo seed volume, Playwright screenshot automation, native mobile API polish, and additional payment/SMS production drivers when credentials are provided.',
        ],
        'status' => [
            'title' => 'Status',
            'body' => 'Application health: /up. Detailed platform diagnostics live in the /admin console when authenticated as a platform operator.',
        ],
        'careers' => [
            'title' => 'Careers',
            'body' => 'Tenant careers pages are powered by the Recruitment (ATS) module. Platform hiring is not listed here — this is a product surface for customer job openings once seeded.',
        ],
        'help' => [
            'title' => 'Help Center',
            'body' => 'Module docs are generated from manifests. For operators: toggle modules under /app → Modules. For guards: /guard PWA. For clients: /client portal.',
        ],
        'blog' => [
            'title' => 'Resources / Blog',
            'body' => 'Demo content placeholder. Articles will list here when published; until then this page states clearly that no customer case studies are claimed.',
        ],
        'case-studies' => [
            'title' => 'Case studies',
            'body' => 'Demo content — no real customer logos or results are shown. Replace with approved stories before public launch marketing.',
        ],
        'privacy' => [
            'title' => 'Privacy policy',
            'body' => 'Tenants control retention via the Privacy module. Biometric and location data require consent. DSAR export/erase workflows are implemented in-app. Contact the tenant data controller for subject requests against a specific deployment.',
        ],
        'terms' => [
            'title' => 'Terms of service',
            'body' => 'Use of this software is governed by your commercial agreement with the platform operator. Acceptable use prohibits unlawful surveillance and misuse of biometric data.',
        ],
        'cookies' => [
            'title' => 'Cookie policy',
            'body' => 'Essential session cookies authenticate panels. Analytics cookies are gated behind the cookie-consent banner and remain off until accepted.',
        ],
        'dpa' => [
            'title' => 'Data processing agreement',
            'body' => 'A formal DPA template should be executed between the platform operator and each tenant. Technical measures are described on the Security page.',
        ],
        'biometric-consent' => [
            'title' => 'Biometric & location consent notice',
            'body' => 'Biometric templates are stored encrypted via attendance-hub. Location tracking is limited to active shift windows with an on-device indicator. Consent records are stored in the Privacy module before enrolment.',
        ],
        'acceptable-use' => [
            'title' => 'Acceptable use',
            'body' => 'Do not use GuardOps to track people outside authorised duty contexts, to circumvent labour law, or to process data without a lawful basis and recorded consent where required.',
        ],
        'quote' => [
            'title' => 'Request a quote / demo',
            'body' => 'Submit the form to create a lead in the CRM module (when enabled for the platform intake tenant). Demo mode stores leads locally.',
        ],
    ],

    'demo_users' => [
        ['role' => 'Platform Super Admin', 'email' => 'superadmin@demo.guard', 'panel' => 'admin'],
        ['role' => 'Company Owner (Falcon)', 'email' => 'owner@falcon.demo', 'panel' => 'app'],
        ['role' => 'Operations Manager', 'email' => 'ops@falcon.demo', 'panel' => 'app'],
        ['role' => 'HR Manager', 'email' => 'hr@falcon.demo', 'panel' => 'app'],
        ['role' => 'Finance', 'email' => 'finance@falcon.demo', 'panel' => 'app'],
        ['role' => 'Scheduler', 'email' => 'scheduler@falcon.demo', 'panel' => 'app'],
        ['role' => 'Supervisor', 'email' => 'supervisor@falcon.demo', 'panel' => 'guard'],
        ['role' => 'Control Room', 'email' => 'control@falcon.demo', 'panel' => 'control'],
        ['role' => 'Guard', 'email' => 'guard@falcon.demo', 'panel' => 'guard'],
        ['role' => 'Trainer', 'email' => 'trainer@falcon.demo', 'panel' => 'app'],
        ['role' => 'Auditor', 'email' => 'auditor@falcon.demo', 'panel' => 'app'],
        ['role' => 'Client Admin', 'email' => 'client@falcon.demo', 'panel' => 'client'],
        ['role' => 'Client Viewer', 'email' => 'clientview@falcon.demo', 'panel' => 'client'],
        ['role' => 'Sentinel Owner', 'email' => 'owner@sentinel.demo', 'panel' => 'app'],
    ],
];
