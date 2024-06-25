<?php

namespace ProjectCode\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use ProjectCode\User\Models\User;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'tasks';

    protected $guarded = [];

    protected $with = ['status', 'members', 'priority', 'tags', 'category'];
    protected $appends = ['member_user_ids'];

    /**
     * Get the status associated with the task.
     */
    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    /**
     * Get the phone priority with the task.
     */
    public function priority(): HasOne
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    /**
     * Get the phone priority with the task.
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Interact with the user's address.
     */
    protected function memberUserIds(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $members = $this->members();
                return $members->pluck('user_id');
            },
        );
    }

    /**
     * Get all of the members for the task.
     */
    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class, //Deployment::class,
            TaskMember::class, //Environment::class,
            'task_id', // Foreign key on the members table...
            'id', // Foreign key on the users table...
            'id', // Local key on the task table...
            'user_id' // Local key on the member table...
        );
    }



    /**
     * Get all of the members for the task.
     */
    public function tags(): HasManyThrough
    {
        return $this->hasManyThrough(
            Tag::class, //Deployment::class,
            TaskTag::class, //Environment::class,
            'task_id', // Foreign key on the members table...
            'id', // Foreign key on the users table...
            'id', // Local key on the task table...
            'tag_id' // Local key on the member table...
        );
    }
}
