<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModals();
        $this->configureUrl();
        $this->defineAuthMacro();
    }

    /**
     * Configure's the applications commands
     *
     * @return void
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );
    }

    /**
     * Configure's the applications modals
     *
     * @return void
     */
    private function configureModals(): void
    {

        Model::shouldBeStrict();
        Model::unguard();
    }

    private function configureUrl(): void
    {
        URL::forceScheme('https');
    }

    /**
     * configure the auth macro
     *
     * @return void
     */
    private function defineAuthMacro(): void
    {
        Auth::macro('merchant', function(){
            return Auth::user()->merchant;
        });
    }
}
