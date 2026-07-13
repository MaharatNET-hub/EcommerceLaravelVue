<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Fallback so routes outside the {locale} prefix (e.g. /admin/*)
        // can still build locale-prefixed named routes such as `login` or
        // `verification.notice` when redirecting guests. SetLocale
        // overrides this with the real locale for prefixed requests.
        URL::defaults(['locale' => config('app.locale')]);

        // password.reset and verification.verify now live inside the
        // {locale} route group, so the default notification URL builders
        // (which don't know about our locale param) need to pass it
        // explicitly, using the locale active when the notification fires.
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url(route('password.reset', [
                'locale' => app()->getLocale(),
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ], false));
        });

        VerifyEmail::createUrlUsing(function ($user) {
            return URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'locale' => app()->getLocale(),
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
        });
    }
}
