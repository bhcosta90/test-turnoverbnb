<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Interfaces\UserInterface;
use App\Models\User;

use function auth;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Password::defaults(function () {
            if (app()->isProduction()) {
                return Password::min(12)->mixedCase()->letters()->numbers()->symbols();
            }

            return Password::min(8);
        });

        $this->app->singleton(UserInterface::class, fn () => new class () implements UserInterface {
            public function getUserLogin(): User
            {
                return auth()->user();
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
