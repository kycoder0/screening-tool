<?php

namespace App\Providers;

use App\Services\OutcomeService;
use App\Services\QuestionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OutcomeService::class, function ($app) {
            return new OutcomeService();
        });

        $this->app->singleton(QuestionService::class, function ($app) {
            return new QuestionService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
