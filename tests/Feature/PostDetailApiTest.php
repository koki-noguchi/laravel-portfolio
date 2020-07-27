<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            'id' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'post_title' => $post->post_title,
                'about' => $post->about,
                'password_judge' => true,
                'bookmarked_by_user' => false,
                'user' => [
                    'name' => $post->user->name,
                    'url' => '/images/default-image.jpeg',
                ],
                'messages' => $post->messages
                    ->sortByDesc('created_at')
                    ->map(function ($message) {
                        return [
                            'id' => $message->id,
                            'message_text' => $message->message_text,
                            'my_message' => false,
                            'author' => [
                                'name' => $message->author->name,
                                'url' => '/images/default-image.jpeg',
                            ],
                        ];
                    })->all(),
            ]);
    }
}
