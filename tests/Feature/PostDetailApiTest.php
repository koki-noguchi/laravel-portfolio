<?php

namespace Tests\Feature;

use App\Post;
use App\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却()
    {
        factory(Post::class)->create()->each(function ($post) {
            $post->messages()->saveMany(factory(Message::class, 3)->make());
        });
        $post = Post::first();

        $response = $this->json('GET', route('post.show', [
            'id' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'about' => $post->about,
                'post_title' => $post->post_title,
                'user' => [
                    'name' => $post->user->name,
                ],
                'messages' => $post->messages
                    ->sortByDesc('id')
                    ->map(function ($message) {
                        return [
                            'author' => [
                                'name' => $message->author->name,
                            ],
                            'message_text' => $message->message_text,
                        ];
                    })->all(),
                'bookmarked_by_user' => false,
            ]);
    }
}
