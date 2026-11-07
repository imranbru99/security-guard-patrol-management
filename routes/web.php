<?php

declare(strict_types=1);

use App\Http\Controllers\Public\DemoLoginController;
use App\Http\Controllers\Public\MarketingController;
use App\Http\Controllers\Public\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'home'])->name('public.home');
Route::get('/features', [MarketingController::class, 'features'])->name('public.features');
Route::get('/features/{slug}', [MarketingController::class, 'featureShow'])->name('public.features.show');
Route::get('/solutions', [MarketingController::class, 'solutions'])->name('public.solutions');
Route::get('/solutions/{slug}', [MarketingController::class, 'solutionShow'])->name('public.solutions.show');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('public.pricing');
Route::get('/demo', [MarketingController::class, 'demo'])->name('public.demo');
Route::post('/demo/login', DemoLoginController::class)->name('public.demo.login');
Route::get('/verify/{code}', [MarketingController::class, 'verify'])->name('public.verify');
Route::post('/quote', [MarketingController::class, 'quoteStore'])->name('public.quote.store');

$staticPages = [
    'about' => 'about',
    'contact' => 'contact',
    'security' => 'security',
    'ai' => 'ai',
    'mobile' => 'mobile',
    'integrations' => 'integrations',
    'api-docs' => 'api',
    'compare' => 'compare',
    'tour' => 'tour',
    'changelog' => 'changelog',
    'roadmap' => 'roadmap',
    'status' => 'status',
    'careers' => 'careers',
    'help' => 'help',
    'blog' => 'blog',
    'case-studies' => 'case-studies',
    'privacy' => 'privacy',
    'terms' => 'terms',
    'cookies' => 'cookies',
    'dpa' => 'dpa',
    'biometric-consent' => 'biometric-consent',
    'acceptable-use' => 'acceptable-use',
    'quote' => 'quote',
];

foreach ($staticPages as $uri => $key) {
    Route::get('/'.$uri, fn () => app(MarketingController::class)->page($key))
        ->name('public.'.$uri);
}

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('public.robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('public.llms');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('public.sitemap');
Route::get('/rss.xml', [SeoController::class, 'rss'])->name('public.rss');
