<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\ValidateApiKey;
use App\Http\Middleware\AdsTrackingRateLimit;
use App\Http\Middleware\JournalContextMiddleware;
use App\Http\Middleware\DetectJournalContext;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('install')
                ->name('install.')
                ->group(base_path('routes/install.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $journal = $request->route('journal');
            if ($journal) {
                $slug = $journal instanceof \App\Models\Journal ? $journal->slug : $journal;
                return route('journal.login', $slug);
            }
            return route('login');
        });

        $middleware->validateCsrfTokens(except: [
            '*/oai', // Allow OAI-PMH POST requests without CSRF token
        ]);

        $middleware->append(App\Http\Middleware\RedirectIfNotInstalled::class);

        $middleware->alias([
            'installed' => \App\Http\Middleware\RedirectIfInstalled::class,
            'not_installed' => \App\Http\Middleware\RedirectIfNotInstalled::class,
            'validate_api_key' => ValidateApiKey::class,
            'ads_rate_limit' => AdsTrackingRateLimit::class,
            'journal.context' => JournalContextMiddleware::class,
            'journal.detect' => DetectJournalContext::class,
            // Spatie Permission middleware
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Trust Railway's reverse proxy so Laravel generates correct HTTPS URLs.
        // Railway terminates SSL and forwards requests via HTTP internally.
        $middleware->trustProxies(at: '*');
    })
    ->withSchedule(function (Schedule $schedule) {
        // Lebih aman pisahkan argumen
        $schedule->command('fetch:phri-news', ['1w'])->dailyAt('01:00');
        $schedule->command('phri:sync-provinces')->dailyAt('02:00');
        $schedule->command('phri:sync-regencies')->dailyAt('02:15');
        $schedule->command('phri:sync-meetingrooms')->dailyAt('02:30');
        $schedule->command('events:fetch')->dailyAt('01:45');
        $schedule->command('media:sync-phri --pages=2')->everySixHours()
            ->withoutOverlapping()
            ->onOneServer();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
