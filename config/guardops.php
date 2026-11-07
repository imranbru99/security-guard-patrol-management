<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo mode
    |--------------------------------------------------------------------------
    |
    | When true, destructive platform actions are disabled, outbound SMS /
    | payments use fake drivers, AI falls back to canned responses without
    | keys, and the UI shows a "Demo — data resets nightly" banner.
    |
    */
    'demo_mode' => (bool) env('DEMO_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Reserved URL slugs
    |--------------------------------------------------------------------------
    |
    | Words that must never be used as tenant or content slugs. Keep in sync
    | with laravel-universal-slug ReservedSlugs registration.
    |
    */
    'reserved_slugs' => [
        'app',
        'guard',
        'client',
        'control',
        'admin',
        'platform',
        'demo',
        'pricing',
        'features',
        'industries',
        'login',
        'register',
        'api',
        'docs',
        'drutogoti',
        'ai-hub',
        'ai-catalog',
        'iclock',
        'up',
        'status',
        'sitemap',
        'llms',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported locales
    |--------------------------------------------------------------------------
    */
    'locales' => [
        'en',
        'bn',
        'ar',
        'hi',
        'es',
        'fr',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default currency (ISO 4217)
    |--------------------------------------------------------------------------
    */
    'default_currency' => env('GUARDOPS_DEFAULT_CURRENCY', 'USD'),

];
