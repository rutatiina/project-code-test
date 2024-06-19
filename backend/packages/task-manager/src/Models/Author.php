<?php

namespace TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use SoftDeletes;

    protected $table = 'authors';

    protected $primaryKey = 'id';

    protected $guarded = [];

    /**
     * The relationship counts that should be eager loaded on every query.
     *
     * @var array
     */
    protected $withCount = ['posts'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['tags'];

    /**
     * Get the posts with the tag.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function getTagsAttribute()
    {
        $tags = [];

        $post_tag = Post::where('author_id', $this->id)->distinct()->get(['tag']);
        $post_tags = Post::where('author_id', $this->id)->get(['tags']);

        foreach ($post_tag as $t) {
            $tags[] = $t->tag;
        }

        foreach ($post_tags as $t) {
            $tags = array_merge($tags, $t->tags);
        }

        $tags = array_unique($tags); //remove duplicates
        $tags = array_filter($tags); //remove null or empty values

        return $tags;
    }
}
