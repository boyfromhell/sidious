<?php
namespace Modules\Economy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Economy\Contracts\EconomicActor;
use Modules\Economy\Traits\HasEconomy;
use Modules\Galaxy\Models\Planet;
use Modules\Inventory\Contracts\HasInventoryContract;
use Modules\Inventory\Traits\HasInventory;
use Nicolasey\Personnages\Models\Personnage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Company extends Model implements HasInventoryContract, EconomicActor
{
    use SoftDeletes, HasEconomy, HasSlug, HasInventory;

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
     * Company location
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }

    /**
     * Personnages allowed to manage this company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function managers()
    {
        return $this->belongsToMany(Personnage::class);
    }
}
