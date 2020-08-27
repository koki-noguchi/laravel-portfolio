<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Message;
use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostDetailApiTest extends TestCase
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
    public function should_正しい構造のJSONを返却()
    {
        factory(Post::class)->create()->each(function ($post) {
            $post->messages()->saveMany(factory(Message::class, 3)->make());
        });
        $post = Post::first();

        $response = $this->actingAs($this->user)->json('GET', route('post.show', [
            'post' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'post_title' => $post->post_title,
                'about' => $post->about,
                'password_judge' => true,
                'bookmarked_by_user' => false,
                'my_post' => false,
                'limit_judge' => true,
                'photos' => [],
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'url' => '/images/default-image.jpeg',
                    'followed_judge' => false,
                    'follow_count' => 0,
                    'follower_count' => 0,
                ],
                'messages' => $post->messages
                    ->sortByDesc('created_at')
                    ->map(function ($message) {
                        return [
                            'id' => $message->id,
                            'message_text' => $message->message_text,
                            'my_message' => false,
                            'author' => [
                                'id' => $message->author->id,
                                'name' => $message->author->name,
                                'url' => '/images/default-image.jpeg',
                                'followed_judge' => false,
                                'follow_count' => 0,
                                'follower_count' => 0,
                            ],
                            'replies_count' => 0,
                        ];
                    })->all(),
            ]);
    }

    /**
     * @test
     */
    public function should_画像一覧を取得()
    {
        factory(Post::class)->create();
        factory(Photo::class, 3)->create();

        $post = Post::first();

        $response = $this->actingAs($this->user)->json('GET', route('post.show', [
            'post' => $post->id,
        ]));

        $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $post->id,
            'post_title' => $post->post_title,
            'about' => $post->about,
            'password_judge' => true,
            'bookmarked_by_user' => false,
            'user' => [
                'id' => $post->user->id,
                'name' => $post->user->name,
                'url' => '/images/default-image.jpeg',
                'followed_judge' => false,
                'follow_count' => 0,
                'follower_count' => 0,
            ],
            'photos' => $post->photos
                    ->map(function ($photo) {
                        return [
                            'id' => $photo->id,
                            'photos_url' => Storage::cloud()->url($photo->post_photo),
                        ];
                    })->all(),
        ]);
    }
}
