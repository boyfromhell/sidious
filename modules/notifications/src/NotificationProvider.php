<?php
namespace Modules\Notifications;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class NotificationProvider extends ServiceProvider
{
    private $namespace = "Modules\Notifications\Controllers";

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapApiRoutes();
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");
    }

    /**
     * Add api routes in provider using standard 'api' middleware
     *
     * @return void
     */
    private function mapApiRoutes()
    {
        Route::namespace($this->namespace)
            ->middleware("api")
            ->prefix("api")
            ->group(function () {
                require __DIR__. '/../routes.php';
            });
    }
}
