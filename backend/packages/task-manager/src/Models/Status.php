<?php

namespace ProjectCode\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'statuses';

    protected $guarded = [];

    protected $appends = ['vue_ref'];

    protected function vueRef(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Str::of($attributes["slug"])->camel()->ucfirst(),
        );
    }
}
