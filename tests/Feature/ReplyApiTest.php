<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplyApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ])->each(function ($post) {
            $post->messages()->saveMany(factory(Message::class, 3)->make());
        });
        $this->post = Post::first();
        $this->message = Message::first();
    }

    /**
     * @test
     */
    public function should_返信ができる()
    {
        $reply_text = 'sample reply';

        $response = $this->actingAs($this->user)
            ->json('POST', route('reply.create', [
                'post' => $this->post->id,
                'message' => $this->message->id,
            ]), compact('reply_text'));

        $reply = $this->message->replies()->get();

        $response->assertStatus(201)
            ->assertJsonFragment([
                "reply_user" => [
                    "name" => $this->user->name,
                ],
                "reply_text" => $reply_text,
            ]);

        $this->assertEquals(1, $reply->count());

        $this->assertEquals($reply_text, $reply[0]->reply_text);
    }

    /**
     * @test
     */
    public function should_メッセージと返信を取得できる()
    {
        $reply_text = 'sample reply';

        $this->actingAs($this->user)
            ->json('POST', route('reply.create', [
                'post' => $this->post->id,
                'message' => $this->message->id,
            ]), compact('reply_text'));

        $response = $this->json('GET', route('reply.show', [
            'post' => $this->post->id,
            'message' => $this->message->id,
        ]));

        $reply = $this->message->replies()->first();

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $reply->id,
                'author' => [
                    'name' => $this->message->author->name,
                ],
                'message_text' => $this->message->message_text,
                'reply_user' => [
                    'name' => $this->user->name,
                ],
                'reply_text' => $reply_text,
            ]);

    }
}
