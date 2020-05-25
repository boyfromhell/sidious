<?php
namespace Modules\Factions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Economy\Contracts\EconomicActor;
use Modules\Economy\Traits\HasEconomy;
use Modules\Inventory\Contracts\HasInventoryContract;
use Modules\Inventory\Traits\HasInventory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Group extends Model implements HasMedia, HasInventoryContract, EconomicActor
{
    use SoftDeletes, HasSlug, HasEconomy, InteractsWithMedia, HasInventory;

    protected $table = "groups";
    protected $guarded = [];

    /**
     * Register media collections for the personnage
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    /**
     * Filter secret groups
     *
     * @param bool $isSecret
     * @return mixed
     */
    public function scopeSecret($query, bool $isSecret)
    {
        return $query->where('isSecret', $isSecret);
    }

    /**
     * Keeps only factions, and no groups
     *
     * @param bool $isFaction
     * @return mixed
     */
    public function scopeFaction($query, bool $isFaction)
    {
        return $query->where('isFaction', $isFaction);
    }

    /**
     * Get the options for generating the slug.
     *
     * @return mixed
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
