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

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->follow_user = factory(User::class)->create();

    }

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
                    'followed_judge' => false,
                    'follow_count' => 0,
                    'follower_count' => 0,
                ],
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

    /**
     * @test
     */
    public function should_フォロー中のユーザーの募集一覧を取得できる()
    {

        factory(Post::class, 5)->create([
            'user_id' => $this->follow_user->id,
        ]);
        factory(Post::class, 5)->create([
            'user_id' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->json('PUT', route('follow.add', [
                'id' => $this->follow_user->id,
            ]));

        $response = $this->actingAs($this->user)->json('GET', route('post.timeline'));

        $posts = Post::where('user_id', $this->follow_user->id)->with(['user', 'photos'])->orderBy('created_at', 'desc')->get();

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
                    'followed_judge' => true,
                    'follow_count' => 0,
                    'follower_count' => 1,
                ],
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
