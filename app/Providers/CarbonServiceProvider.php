<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class CarbonServiceProvider extends ServiceProvider
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
        // Completely disable Carbon translation to avoid issues
        Carbon::setLocale('en');
        
        // Set to use simple English without translation
        $translator = Carbon::getTranslator();
        if ($translator && method_exists($translator, 'setLocale')) {
            $translator->setLocale('en');
        }
        
        // Disable human diff by default for the session
        Carbon::setHumanDiffOptions(0);
    }
}
