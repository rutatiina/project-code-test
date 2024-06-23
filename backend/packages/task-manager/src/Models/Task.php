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

    protected $with = ['status', 'members', 'priority', 'tags'];
    // protected $appends = ['members'];

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
     * Get all of the members for the task.
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
