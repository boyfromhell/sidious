<?php

namespace Modules\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Forum extends Model implements HasMedia
{
    use SoftDeletes, NodeTrait, HasSlug, InteractsWithMedia;

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
        $this->addMediaCollection('forums_image')->singleFile();
    }

    protected $guarded = [];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function topicPosts()
    {
        return $this->hasManyThrough(Post::class, Topic::class);
    }

    public function latestTopicsPost()
    {
        return $this->topicPosts()->latest("created_at");
    }

    public function latestDescendantsPost()
    {
        $last = null;
        foreach($this->descendants as $descendant) {
            if($descendant->last) {
                if($descendant->last->id > $last->id) $last = $descendant->last;
            }
        }
        return $last;
    }

    /**
     * Evaluate last post from descendants and topics
     * Sets correct last post to this forum
     *
     * @return void
     */
    public function evaluateLastPost()
    {
        $last = Collection::make($this->latestDescendantsPost(), $this->latestTopicsPost())
            ->last();

        // Set last with correct answer
        $this->last_id = $last->id;
        $this->save();

        // Spread the word if necessary
        if($this->parent_id) {
            if($this->parent->last->id < $last) $this->parent->evaluateLastPost();
        }
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            // softDelete child elements
            $model->topics()->delete();
            $model->children()->delete();

            // make the parent check lastPost accuracy
            if($model->parent) $model->parent->evaluateLastPost();
        });
    }

    public function last()
    {
        return $this->belongsTo(Post::class, "last_id");
    }
}
