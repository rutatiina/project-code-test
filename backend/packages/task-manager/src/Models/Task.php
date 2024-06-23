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

    protected $with = ['status', 'members', 'priority'];
    // protected $appends = ['members'];

    /**
     * Get the phone associated with the user.
     */
    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    /**
     * Get the phone associated with the user.
     */
    public function priority(): HasOne
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    // protected function members(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => [1, 2, 3, 4],
    //     );
    // }

    /**
     * Get all of the members for the project.
     */
    public function members(): HasManyThrough
    {
        /*
        tasks - projects
            id - integer
            name - string
        
        members - environments
            id - integer
            task_id - project_id - integer
            name - string
        
        users - deployments
            id - integer
            environment_id - integer
            commit_hash - string
        */
        return $this->hasManyThrough(
            User::class, //Deployment::class,
            Member::class, //Environment::class,
            'task_id', // Foreign key on the members table...
            'id', // Foreign key on the users table...
            'id', // Local key on the task table...
            'user_id' // Local key on the member table...
        );
    }
}
