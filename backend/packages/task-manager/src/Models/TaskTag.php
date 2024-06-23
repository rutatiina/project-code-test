<?php

namespace ProjectCode\TaskManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTag extends Model
{
    use SoftDeletes;

    protected $table = 'task_tags';

    protected $guarded = [];
}
