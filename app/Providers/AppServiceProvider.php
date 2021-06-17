<?php

namespace App\Providers;

use App\Models\Quote;
use App\Observers\QuoteObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'dev')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::macro(
            'toUserTimezone',
            function ($timezone = null) {
                if (null == $timezone) {
                    $timezone = auth()->user()->timezone ?? 'Europe/Zagreb';
                }

                /* @var Carbon $this */
                return $this->timezone($timezone);
            }
        );

        Quote::observe(QuoteObserver::class);
    }
}
