<?php

namespace TaskManager\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Department extends Model
{
    use SoftDeletes;

    protected $table = 'departments';

    protected $primaryKey = 'id';

    protected $guarded = [];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $appends = ['brief'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', "active");
        });
    }

    protected function brief(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($value) return $value;

                // return $summary = htmlspecialchars($summary);
                // return $summary = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $summary); 
                $summary = preg_replace("/&#?[a-z0-9]+;/i", "", $attributes['description']);
                return Str::limit($summary, 60, ' ...');
            }
        );
    }
}
