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

        $messages = $post->messages()->first();

        $response->assertStatus(201)
                ->assertJsonFragment([
                    "id" => $messages->id,
                    "message_text" => $messages->message_text,
                    "my_message" => true,
                    "replies_count" => 0,
                ]);

        $this->assertEquals(1, $messages->count());
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
                'message' => $message->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('messages', [
            'message' => $message->id,
        ]);
    }

    /**
     * @test
     */
    public function should_メッセージ一覧を取得できる()
    {
        factory(Post::class)->create();
        $post = Post::first();

        $message_text = 'sample content';

        $this->actingAs($this->user)
            ->json('POST', route('post.message', [
                'post' => $post->id,
            ]), compact('message_text'));

        $message = Message::first();

        $response = $this->actingAs($this->user)
            ->json('GET', route('message.show', [
                'post' => $post->id,
            ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $message->id,
                'message_text' => $message->message_text,
                'my_message' => true,
                'replies_count' => 0,
            ]);
    }
}
