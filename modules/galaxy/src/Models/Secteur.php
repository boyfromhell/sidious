<?php

namespace Modules\Galaxy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Economy\Contracts\EconomicActor;
use Modules\Economy\Traits\HasEconomy;
use Modules\Inventory\Contracts\HasInventoryContract;
use Modules\Inventory\Traits\HasInventory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Secteur extends Model implements HasMedia, HasInventoryContract, EconomicActor
{
    use SoftDeletes, NodeTrait, HasSlug, InteractsWithMedia, HasInventory, HasEconomy;

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
        $this->addMediaCollection('secteurs_image')->singleFile();
    }

    protected $guarded = [];

    /**
     * Secteur's planets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planets()
    {
        return $this->hasMany(Planet::class);
    }

    public static function boot()
    {
        parent::boot();

        // Cascade delete
        static::deleted(function($model) {
            $model->planets()->delete();
            $model->children()->delete();
        });
    }
}
