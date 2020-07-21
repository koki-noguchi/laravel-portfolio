<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageApiTest extends TestCase
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
    public function should_メッセージを追加できる()
    {
        factory(Post::class)->create();
        $post = Post::first();

        $message_text = 'sample content';

        $response = $this->actingAs($this->user)
            ->json('POST', route('post.message', [
                'post' => $post->id,
            ]), compact('message_text'));

        $messages = $post->messages()->get();

        $response->assertStatus(201)
                ->assertJsonFragment([
                    "author" => [
                        "name" => $this->user->name,
                    ],
                    "message_text" => $message_text,
                ]);

        $this->assertEquals(1, $messages->count());

        $this->assertEquals($message_text, $messages[0]->message_text);
    }

    /**
     * @test
     */
    public function should_メッセージを削除できる()
    {
        factory(Post::class)->create()
        ->each(function ($post) {
            $post->messages()->save(factory(Message::class)->make([
                'user_id' => $this->user->id,
            ]));
        });

        $message = Message::first();

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('message.delete', [
                'id' => $message->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('messages', [
            'id' => $message->id,
        ]);
    }
}
