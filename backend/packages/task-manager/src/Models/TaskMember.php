<?php

namespace ProjectCode\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskMember extends Model
{
    use SoftDeletes;

    protected $table = 'task_members';

    protected $guarded = [];
}
