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
                'message' => $this->message->id,
            ]), compact('reply_text'));

        $reply = $this->message->reply()->get();

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
}
