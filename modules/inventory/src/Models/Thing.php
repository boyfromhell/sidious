<?php
namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

/**
 * Object classification (without improvements)
 *
 * Class Object
 * @package Modules\Inventory
 */
class Thing extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, HasTags, HasMediaTrait;

    protected $table = "objects";

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
    public function registerMediaCollections()
    {
        $this->addMediaCollection('things_image')->singleFile();
    }

    /**
     * Object type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(ObjectType::class);
    }
}
