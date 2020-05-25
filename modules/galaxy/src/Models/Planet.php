<?php

namespace Modules\Galaxy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Economy\Contracts\EconomicActor;
use Modules\Economy\Traits\HasCompanies;
use Modules\Economy\Traits\HasEconomy;
use Modules\Factions\Traits\InGroups;
use Modules\Inventory\Contracts\HasInventoryContract;
use Modules\Inventory\Traits\HasInventory;
use Modules\Personnages\Models\Personnage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Planet extends Model implements HasMedia, HasInventoryContract, EconomicActor
{
    use SoftDeletes, HasSlug, HasCompanies, HasEconomy, InteractsWithMedia, InGroups, HasInventory;

    protected $guarded = [];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('planets_image')->singleFile();
    }

    /**
     * Planet's secteur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    /**
     * Planet's managers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Personnage::class);
    }
}
