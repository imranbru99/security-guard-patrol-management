@php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                'name' => 'GuardOps',
                'url' => url('/'),
                'description' => 'Security guard and patrol management software for shifts, checkpoints, and incidents.',
            ],
            [
                '@type' => 'SoftwareApplication',
                'name' => 'GuardOps',
                'applicationCategory' => 'BusinessApplication',
                'operatingSystem' => 'Web',
                'url' => url('/'),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'USD',
                    'description' => 'Interactive plan builder on /pricing — final pricing set by the platform operator.',
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
