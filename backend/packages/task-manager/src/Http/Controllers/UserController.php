<?php

namespace TaskManager\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TaskManager\Models\Post;
use TaskManager\Models\Author;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use TaskManager\Traits\ImageManagerTrait;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    use ImageManagerTrait;

    // >> get the item attributes template << !!important

    public function __construct()
    {
        //
    }

    public function profile()
    {
        // $posts = Post::withoutGlobalScope('approved')->paginate(50);
        $posts = Post::withoutGlobalScope('approved')->where('user_id', Auth::id())->paginate(50);
        $user = User::find(Auth::id());

        return [
            "user" => $user,
            "posts" => $posts,
        ];
    }
}
