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
    }

    /**
     * @test
     */
    public function should_ユーザーの募集履歴は取得できるが、ブックマークは取得できない()
    {
        $user = factory(User::class)->create();

        factory(Post::class)->create([
            'user_id' => $user->id,
        ]);
        $this->post = Post::first();

        $this->actingAs($user)
            ->json('PUT', route('bookmark.add', [
                'id' => $this->post->id,
            ]));

        $response = $this->actingAs($user)
                ->json('GET', route('user.profile', [
                    'id' => $user->id,
                ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'url' => '/images/default-image.jpeg',
                'posts' => [[
                    'id' => $this->post->id,
                    'post_title' => $this->post->post_title,
                    'about' => $this->post->about,
                    'updated_at' => $this->post->updated_at,
                    'password_judge' => true,
                    'bookmarked_by_user' => true,
                    'my_post' => true,
                    'limit_judge' => false,
                    'user' => [
                        'id' => (int) $this->post->user_id,
                        'name' => $user->name,
                        'url' => '/images/default-image.jpeg',
                    ],
                    'photos' => [],
                ]],
            ])
            ->assertJsonMissing([
                'bookmark_post' => [[
                    'id' => $this->post->id,
                ]]
            ]);
    }

    /**
     * @test
     */
    public function should_ログイン中ユーザーの募集履歴もブックマークも取得できる()
    {
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);
        $this->post = Post::first();

        $this->actingAs($this->user)
            ->json('PUT', route('bookmark.add', [
                'id' => $this->post->id,
            ]));

        $response = $this->actingAs($this->user)
        ->json('GET', route('user.profile', [
            'id' => $this->user->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->user->id,
                'name' => $this->user->name,
                'login_id' => $this->user->login_id,
                'url' => '/images/default-image.jpeg',
                'posts' => [[
                    'id' => $this->post->id,
                    'post_title' => $this->post->post_title,
                    'about' => $this->post->about,
                    'updated_at' => $this->post->updated_at,
                    'password_judge' => true,
                    'bookmarked_by_user' => true,
                    'my_post' => true,
                    'limit_judge' => false,
                    'user' => [
                        'id' => (int) $this->post->user_id,
                        'name' => $this->user->name,
                        'url' => '/images/default-image.jpeg',
                    ],
                    'photos' => [],
                ]],
                'bookmark_post' => [[
                    'id' => $this->post->id,
                    'post_title' => $this->post->post_title,
                    'about' => $this->post->about,
                    'updated_at' => $this->post->updated_at,
                    'password_judge' => true,
                    'bookmarked_by_user' => true,
                    'my_post' => true,
                    'limit_judge' => false,
                    'user' => [
                        'id' => (int) $this->post->user_id,
                        'name' => $this->user->name,
                        'url' => '/images/default-image.jpeg',
                    ],
                    'photos' => [],
                ]],
            ]);
    }
}
