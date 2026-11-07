<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Services;

use App\Modules\Visitors\Models\Visit;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

final class VisitorBadgePdf
{
    public function download(Visit $visit): Response
    {
        $visit->loadMissing('visitor', 'site');

        $visitorName = $visit->visitor !== null ? (string) $visit->visitor->full_name : 'Visitor';
        $siteName = $visit->site !== null ? (string) $visit->site->name : '—';
        $badge = (string) $visit->badge_code;
        $host = (string) ($visit->host_name ?? '—');

        $html = '<h1>Visitor badge</h1>'
            .'<p>Stub PDF — present this badge at reception.</p>'
            .'<table border="1" cellpadding="8" cellspacing="0" width="100%">'
            .'<tr><th align="left">Name</th><td>'.e($visitorName).'</td></tr>'
            .'<tr><th align="left">Host</th><td>'.e($host).'</td></tr>'
            .'<tr><th align="left">Site</th><td>'.e($siteName).'</td></tr>'
            .'<tr><th align="left">Badge</th><td>'.e($badge).'</td></tr>'
            .'<tr><th align="left">QR</th><td>'.e('VISITOR:'.$badge).'</td></tr>'
            .'</table>';

        return UnicodePdf::loadHtml($html)->download('visitor-badge-'.$badge.'.pdf');
    }
}
