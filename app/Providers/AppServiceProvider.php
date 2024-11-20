<?php

namespace App\Providers;

use App\Models\Auth\JwtGuard;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\Blog\IBlogRepository;
use App\Repositories\Mail\IMailRepository;
use App\Repositories\Mail\MailRepository;
use App\Repositories\User\IProfileConfirmationRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\ProfileConfirmationRepository;
use App\Repositories\User\UserRepository;
use App\Services\BruteForce\BruteForceProtector;
use App\Services\BruteForce\IBruteForceProtector;
use App\Services\Mail\IMailService;
use App\Services\Mail\MailService;
use App\Services\User\IUserService;
use App\Services\User\UserService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IBruteForceProtector::class, BruteForceProtector::class);
        $this->app->bind(IProfileConfirmationRepository::class, ProfileConfirmationRepository::class);
        $this->app->bind(IMailService::class, MailService::class);
        $this->app->bind(IMailRepository::class, MailRepository::class);
        $this->app->bind(IBlogRepository::class, BlogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the JWT guard
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(Auth::createUserProvider($config['provider']));
        });
    }
}
