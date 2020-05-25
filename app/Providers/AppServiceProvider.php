<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            "Personnage" => "Modules\Personnages\Models\Personnage",
            "Company" => "Modules\Economy\Models\Company",
            "Planet" => "Modules\Galaxy\Models\Planet",
            "Secteur" => "Modules\Galaxy\Models\Secteur",
            "Group" => "Modules\Factions\Models\Group",
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
