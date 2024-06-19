<?php

namespace TaskManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $content = '[{"type":"post-gallery","image":"/upload/news-posts/single4.jpg","caption":"Cras eget sem nec dui volutpat ultrices."},{"type":"post-content","content":["<p>We are good to go now... ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, lacus a ultrices sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.</p><p>Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.</p><p>Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. Vivamus a mauris eget arcu gravida tristique. Nunc iaculis mi in ante. Vivamus imperdiet nibh feugiat est.<blockquote>Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus.</blockquote>"]},{"type":"article-inpost","content":[{"type":"row","columns":[{"type":"col-md-6","content":{"type":"image-content","image-place":{"image":"/upload/news-posts/single-art2.jpg","caption":"Cras eget sem nec dui volutpat ultrices."}}},{"type":"col-md-6","content":{"type":"image-content","image-place":{"image":"/upload/news-posts/single-art3.jpg","caption":"Cras eget sem nec dui volutpat ultrices."}}}]}]},{"type":"post-content","content":["The final paragrah&nbsp; mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. Vivamus a mauris eget arcu gravida tristique. Nunc iaculis mi in ante. Vivamus imperdiet nibh feugiat est."]}]';

        $id = 1;
        $posts = [];

        //11 header posts
        for ($i = 1; $i <= 11; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Lorem ipsum dolor sit amet, consectetuer',
                'summary' => 'Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis.',
                'tags' => [
                    'heading',
                    'gallery'
                ]
            ];
            $id++;
        }

        //10 featured posts
        for ($i = 1; $i <= 11; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Lorem ipsum dolor sit amet, consectetuer',
                'summary' => 'Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis.',
                'tags' => [
                    'featured'
                ]
            ];
            $id++;
        }

        //8 featured-video posts
        for ($i = 1; $i <= 8; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Lorem ipsum dolor sit amet, consectetuer',
                'summary' => 'Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis.',
                'tags' => [
                    'featured-video'
                ]
            ];
            $id++;
        }

        //5 fashion posts
        for ($i = 1; $i <= 5; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Donec odio. Quisque volutpat mattis eros.',
                'summary' => 'Donec odio. Quisque volutpat mattis eros.',
                'tags' => [
                    'fashion'
                ]
            ];
            $id++;
        }

        //6 life-style & instagram posts
        for ($i = 1; $i <= 12; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. ',
                'summary' => 'Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. ',
                'tags' => [
                    'life-style',
                    'instagram'
                ]
            ];
            $id++;
        }

        //12 love posts
        for ($i = 1; $i <= 6; $i++) {
            $posts[] = [
                'id' => $id,
                'title' => 'Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. ',
                'summary' => 'Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. ',
                'tags' => [
                    'love'
                ]
            ];
            $id++;
        }

        foreach ($posts as $post) {

            DB::table('posts')->insertOrIgnore([
                'id' => $post['id'],
                // 'code' => $post['code'],
                'title' => $post['title'],
                'summary' => $post['summary'],
                'slug' => Str::of($post['title'])->slug('-'),
                'author_id' => 1,
                'content' => $content,
                'status' => 'pending',
                'tags' => json_encode($post['tags']),
            ]);
        }
    }
}
