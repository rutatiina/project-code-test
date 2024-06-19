<?php

namespace ProjectCode\TaskManager\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TaskManager\Models\Post;
use TaskManager\Models\Author;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TaskManagerController extends Controller
{

    // >> get the item attributes template << !!important

    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        $authors = Author::all();

        return [
            'authors' => $authors,
            'can_edit' => false
        ];
    }

    public function create(Request $request)
    {
        // return $request;
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|ascii',
            'description' => 'required|ascii',
            'post_id' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $author = new Author();
        $author->name = $request->name;
        $author->slug = Str::of($request->name)->slug('-');
        $author->description = $request->description;

        $author->save();
        $author->refresh(); //this will load the full author model

        //update the post author_id
        // $post = Post::withoutGlobalScope('approved')->find($request->post_id);
        // $post->author_id = $author->id;
        // $post->save();

        return $author;
    }

    public function show($idOrSlug)
    {
        if (is_numeric($idOrSlug)) {
            $author = Author::find($idOrSlug);
        } else {
            $author = Author::where('slug', $idOrSlug)->first();
        }

        $posts_paginated = $author->posts()->paginate(20);

        $author->posts = $posts_paginated;
        $author->posts_chucked = $posts_paginated->chunk(2);

        return [
            'author' => $author,
            'can_edit' => false
        ];
    }

    public function update(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'nullable|ascii',
            'description' => 'nullable',
            'post_id' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $author = Author::find($request->id);

        // if ($request->cover_image_name) $post->cover_image_name = $request->cover_image_name;
        if ($request->name) $author->name = $request->name;
        if ($request->name) $author->slug = Str::of($request->name)->slug('-');
        if ($request->description) $author->description = $request->description;

        $author->save();
        $author->refresh(); //this will load the full author model

        // $post = Post::withoutGlobalScope('approved')->find($request->post_id);
        // $post->author_id = $author->id;
        // $post->save();

        return $author;
    }
}
