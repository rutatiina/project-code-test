<?php

namespace TaskManager\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use TaskManager\Models\Tag;
use TaskManager\Models\Post;
use Butschster\Head\Facades\Meta;
use TaskManager\Models\Author;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use TaskManager\Models\Subscriber;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Butschster\Head\Packages\Entities\OpenGraphPackage;

class TaskManagerController extends Controller
{
    // >> get the item attributes template << !!important

    public function __construct()
    {
        //
    }

    public function index()
    {
        Meta::setTitle('TaskManager');

        if (request()->is('posts/*')) {
            $id = request()->segments()[1];
            if (is_numeric($id)) {
                $post = Post::find($id);
            } else {
                $post = Post::where('slug', $id)->first();
            }

            if (!$post) {
                return view('public::index');
            }

            if ($post->cover_image_path) {
                $og = new OpenGraphPackage('open_graph');
                $og->addImage(url($post->cover_image_path));
                Meta::registerPackage($og);
            }

            Meta::prependTitle($post->title);
            Meta::setDescription(strip_tags($post->summary), 100);
            Meta::setKeywords($post->tags);
        }

        return view('public::index');
    }

    public function user()
    {
        return Auth::user();
    }

    public function initialize(Request $request)
    {
        // dd(env('SESSION_DRIVER'));
        // session(['key' => 'value of rocking']);

        $posts = Post::all()->keyBy('code');
        $posts['kulikoni'] = collect([]);
        $posts['love'] = collect([]);
        $posts['recent_columned'] = collect([]);
        // return $posts['session'] = collect([session('key')]);

        $sportLightTags = ['spotlight', 'the-spotlight', 'sport-light', 'the-sport-light'];

        $posts['topStories'] = Post::whereJsonContains('placement', 'index-page-top-story')->orderBy('id', 'desc')->limit(3)->get()->makeHidden(['content']);

        $posts['heading'] = Post::orderByDesc('id')
            ->whereJsonContains('placement', 'index-page-heading')
            ->whereNotIn('id', $posts['topStories']->pluck('id')->toArray())
            ->limit(8)
            ->get()
            ->makeHidden(['content']);

        $posts['featuredToday'] = Post::orderByDesc('id')->whereJsonContains('placement', 'index-page-featured-today')->limit(6)->get()->makeHidden(['content']);
        $posts['gallery'] = Post::orderByDesc('id')->whereJsonContains('tags', 'gallery')->limit(4)->get()->makeHidden(['content']);

        $posts['sportLight'] = Post::orderByDesc('id')->where(function ($query) use ($sportLightTags) {
            foreach ($sportLightTags as $tag) {
                $query->orWhereJsonContains('tags', $tag);
            }
        })->limit(4)->get()->makeHidden(['content']);

        $posts['featured'] = Post::orderByDesc('id')->whereJsonContains('placement', 'index-page-featured')->limit(6)->get()->makeHidden(['content']);
        $posts['featuredVideo'] = Post::orderByDesc('id')->whereJsonContains('placement', 'index-page-featured-video')->limit(8)->get()->makeHidden(['content']);
        $posts['latestArticles'] = Post::orderBy('id', 'desc')->limit(6)->get()->makeHidden(['content']);
        $posts['popular'] = Post::orderBy('views', 'desc')->limit(5)->get()->makeHidden(['content']);
        $posts['recent'] = Post::orderBy('id', 'desc')->limit(5)->get()->makeHidden(['content']);
        $recent_columned = Post::orderBy('id', 'desc')->limit(12)->get()->makeHidden(['content'])->splitIn(3);
        $posts['topReviews'] = Post::orderBy('views', 'desc')->limit(5)->get()->makeHidden(['content']);
        $posts['random'] = Post::inRandomOrder()->limit(3)->get()->makeHidden(['content']);
        $posts['instagram'] = Post::orderBy('views', 'desc')->whereJsonContains('tags', 'instagram')->orderBy('id', 'desc')->limit(6)->get()->makeHidden(['content']);
        // $kulikoni = Post::inRandomOrder()->whereJsonContains('tags', 'kulikoni')->limit(12)->get()->makeHidden(['content'])->splitIn(3);
        $posts['kulikoni'] = Post::orderBy('views', 'desc')->whereJsonContains('tags', 'kulikoni')->limit(6)->get()->makeHidden(['content']);
        $loves = Post::orderBy('views', 'desc')->whereJsonContains('tags', 'love')->limit(6)->get()->makeHidden(['content'])->splitIn(2);

        // foreach ($kulikoni as $post) {
        //     $posts['kulikoni'][] = array_values($post->all());
        // }
        foreach ($recent_columned as $post) {
            $posts['recent_columned'][] = array_values($post->all());
        }
        foreach ($loves as $lPosts) {
            $posts['love'][] = array_values($lPosts->all());
        }

        $can_edit = (Auth::check() && Auth::id() == 1) ? true : false;


        return [
            "can_edit" => $can_edit,
            "user" => Auth::user(),
            "posts" => $posts,
            "tags" => Tag::withCount('posts')->get()->toArray(),
            "subscribers_count" => Subscriber::count(),
            "adverts" => $this->adverts()
        ];
    }

    public function update()
    {
        return "No updates to run";

        Role::create(['name' => 'editor']);

        /*
        $editorUserIds = [1, 2, 3, 4];
        foreach ($editorUserIds as $value) {
            $user = User::find($value);
            $user->assignRole('editor');
        }

        return "TaskManager update #003 run";


        /*
        //Add the default user
        User::insertOrIgnore([
            'id' => 1,
            'name' => "TaskManager Content",
            'email' => "content@chuotimes.co.tz",
            'password' => Hash::make('chU0Cont3nt')
        ]);

        User::insertOrIgnore([
            'id' => 2,
            'name' => "Shubi Mukolera",
            'email' => "shubi.mukolera@gmail.com",
            'password' => Hash::make('shUb1muk0l3r@')
        ]);

        User::insertOrIgnore([
            'id' => 3,
            'name' => "Faith Disney",
            'email' => "faithdisney77@gmail.com",
            'password' => Hash::make('f@1thdIsn3y')
        ]);

        User::insertOrIgnore([
            'id' => 4,
            'name' => "Mkongwa Doreen",
            'email' => "mkongwadoreen@gmail.com",
            'password' => Hash::make('mk0ngw@dor3en')
        ]);

        //authors slug
        $authors = Author::all();
        foreach ($authors as $author) {
            Author::where('id', $author->id)->update([
                'slug' => Str::of($author->name)->slug('-')
            ]);
        }

        return "Update #002 run";
        //*/
    }

    public function adverts()
    {

        $adverts = [
            '300x250' => [],
            '728x90' => [],
            '160x600' => [],
            '250x250' => [],
        ];

        //read all files is ads folder
        $dir    = storage_path('app/public/adverts');
        $adverts_files = scandir($dir);

        foreach ($adverts_files as  $file) {
            if (str_contains($file, '300x250')) {
                $adverts['300x250'][] = $file;
            }
            if (str_contains($file, '728x90')) {
                $adverts['728x90'][] = $file;
            }
            if (str_contains($file, '160x600')) {
                $adverts['160x600'][] = $file;
            }
            if (str_contains($file, '250x250')) {
                $adverts['250x250'][] = $file;
            }
        }

        return $adverts;
    }

    public function issue($name)
    {
        // return 'issue: '.$name;

        $url = Storage::url(
            'issues/' . $name . '.pdf',
            now()->addMinutes(5)
        );

        $data = [
            'url' => $url,
            'fileName' => 'chuotimes issue ' . $name,
            'issueNumber' => $name
        ];

        return view('issue', $data);
    }

    public function issuesDownload($issueNumber)
    {
        // $file = "/Users/rutatiina/Sites/chuotimes/storage/app/public/issues/chuotimes_issue_7.pdf";
        $file = "/public/issues/chuotimes-issue-" . $issueNumber . ".pdf";
        return Storage::download($file);
    }

    public function artisanOptimizeClear()
    {
        Artisan::call('optimize:clear');
        return nl2br(Artisan::output());
    }
}
