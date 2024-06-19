<?php

namespace TaskManager\Http\Controllers;


use TaskManager\Models\Post;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use TaskManager\Models\Author;

class DashboardController extends Controller
{
    // >> get the item attributes template << !!important

    public function __construct()
    {
        //
    }

    public function admin()
    {
        $postCount = Post::withoutGlobalScope('approved')
            ->select('*', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->setVisible(["status", "total"]);

        return [
            'totalPosts' => Post::withoutGlobalScope('approved')->count(),
            'totalPostsApproved' => optional($postCount->where('status', 'approved')->first())->total ?? 0,
            'totalPostsDeclined' => optional($postCount->where('status', 'declined')->first())->total ?? 0,
            'totalPostsPending' => optional($postCount->where('status', 'pending')->first())->total ?? 0,

            'totalAuthors' => Author::count(),
            'authors' => Author::limit(7)->get()->setVisible(["id", "name", "cover_image_path"]),
        ];
    }
}
