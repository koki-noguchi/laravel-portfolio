<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却()
    {
        factory(Post::class, 5)->create();

        $response = $this->json('GET', route('post.index'));

        $posts = Post::with(['user'])->orderBy('created_at', 'desc')->get();

        $expected_data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'about' => $post->about,
                'post_title' => $post->post_title,
                'password_judge' => $post->post_password ? true : false,
                'updated_at' => $post->updated_at,
                'limit_judge' => false,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'url' => '/images/default-image.jpeg',
                ],
                'messages' => [],
                'photos' => [],
                'bookmarked_by_user' => false,
                'my_post' => false,
            ];
        })
        ->all();

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);
    }
}
