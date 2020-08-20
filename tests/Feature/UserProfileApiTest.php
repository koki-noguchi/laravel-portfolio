<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileApiTest extends TestCase
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
    public function should_ログイン中のユーザーのプロフィールをlogin_id含めて取得できる()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user.profile', [
            'user' => $this->user->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->user->id,
                'login_id' => $this->user->login_id,
                'name' => $this->user->name,
                'url' => '/images/default-image.jpeg',
                'followed_judge' => false,
                'follow_count' => 0,
                'follower_count' => 0,
            ]);
    }

    /**
     * @test
     */
    public function should_他のユーザーのプロフィールをlogin_id含めず取得できる()
    {
        $this->user_another = factory(User::class)->create();
        $response = $this->actingAs($this->user)->json('GET', route('user.profile', [
            'user' => $this->user_another->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->user_another->id,
                'name' => $this->user_another->name,
                'url' => '/images/default-image.jpeg',
                'followed_judge' => false,
                'follow_count' => 0,
                'follower_count' => 0,
            ])
            ->assertJsonMissing(['login_id' => $this->user_another->login_id]);
    }

    /**
     * @test
     */
    public function should_タイムラインを取得できる()
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

        $response = $this->actingAs($this->user)->json('GET', route('user.timeline'));

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

    /**
     * @test
     */
    public function should_ユーザーの募集履歴を取得できる()
    {
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);

        factory(Post::class, 4)->create();

        $this->post = Post::where('user_id', $this->user->id)->first();

        $response = $this->actingAs($this->user)
                ->json('GET', route('user.history', [
                    'user' => $this->user->id,
                ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->post->id,
                'post_title' => $this->post->post_title,
                'about' => $this->post->about,
                'updated_at' => $this->post->updated_at,
                'password_judge' => true,
                'bookmarked_by_user' => false,
                'my_post' => true,
                'limit_judge' => false,
                'user' => [
                    'id' => (int) $this->user->id,
                    'name' => $this->user->name,
                    'url' => '/images/default-image.jpeg',
                    'followed_judge' => false,
                    'follow_count' => 0,
                    'follower_count' => 0,
                ],
                'photos' => [],
            ]);
    }

    /**
     * @test
     */
    public function should_ログイン中ユーザーは、ブックマーク一覧を取得できる()
    {
        $this->user_bookmark = factory(User::class)->create();
        factory(Post::class, 5)->create([
            'user_id' => $this->user_bookmark->id,
        ]);
        $this->post = Post::first();

        $this->actingAs($this->user)
            ->json('PUT', route('bookmark.add', [
                'post' => $this->post->id,
            ]));

        $response = $this->actingAs($this->user)
            ->json('GET', route('user.bookmark'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->post->id,
                'post_title' => $this->post->post_title,
                'about' => $this->post->about,
                'updated_at' => $this->post->updated_at,
                'password_judge' => true,
                'bookmarked_by_user' => true,
                'my_post' => false,
                'limit_judge' => false,
                'user' => [
                    'id' => (int) $this->user_bookmark->id,
                    'name' => $this->user_bookmark->name,
                    'url' => '/images/default-image.jpeg',
                    'followed_judge' => false,
                    'follow_count' => 0,
                    'follower_count' => 0,
                ],
                'photos' => [],
            ]);
    }

    /**
     * @test
     */
    public function should_他のユーザーのブックマークは取得できない()
    {
        $this->user_bookmark = factory(User::class)->create();
        factory(Post::class, 5)->create();
        $this->post = Post::first();

        $this->actingAs($this->user)
            ->json('PUT', route('bookmark.add', [
                'post' => $this->post->id,
            ]));

        $response = $this->actingAs($this->user_bookmark)->json('GET', route('user.bookmark'));
        $response
            ->assertJsonMissing(['id' => $this->post->id]);
    }
}
