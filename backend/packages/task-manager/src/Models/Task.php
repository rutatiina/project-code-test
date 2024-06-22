<?php

namespace ProjectCode\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    protected $guarded = [];

    protected $with = ['status'];
    protected $appends = ['members'];

    /**
     * Get the phone associated with the user.
     */
    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    protected function members(): Attribute
    {
        return Attribute::make(
            get: fn () => [1, 2, 3, 4],
        );
    }
}
