<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_ログイン中ユーザーの返却()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->user->name,
            ]);
    }

    /**
     * @test
     */
    public function should_ログインされていない場合は空文字を返却()
    {
        $response = $this->json('GET', route('user'));

        $response->assertStatus(200);
        $this->assertEquals("", $response->content());
    }

    /**
     * @test
     */
    public function should_ユーザーの募集履歴を取得できる()
    {
        $post = factory(Post::class)->create();

        $response = $this->actingAs($this->user)->json('GET', route('user.profile', [
            'id' => $post->user->id,
        ]));

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $post->user->name,
                'id' => $post->user->id,
                'url' => '/images/default-image.jpeg'
            ]);
    }
}
