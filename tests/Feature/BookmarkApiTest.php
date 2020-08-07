<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarkApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        factory(Post::class)->create();
        $this->post = Post::first();
    }

    /**
     * @test
     */
    public function should_ブックマークに追加できる()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', route('bookmark.add', [
                'id' => $this->post->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'post_id' => $this->post->id,
            ]);

        $this->assertEquals(1, $this->post->bookmarks()->count());
    }

    /**
     * @test
     */
    public function should_1つのメッセージ募集にブックマークを2回できない()
    {
        $param = ['id' => $this->post->id];
        $this->actingAs($this->user)->json('PUT', route('bookmark.add', $param));
        $this->actingAs($this->user)->json('PUT', route('bookmark.add', $param));

        $this->assertEquals(1, $this->post->bookmarks()->count());
    }

    /**
     * @test
     */
    public function should_ブックマークを外せる()
    {
        $this->post->bookmarks()->attach($this->user->id);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('bookmark.delete', [
                'id' => $this->post->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'post_id' => $this->post->id,
            ]);

        $this->assertEquals(0, $this->post->bookmarks()->count());
    }

    /**
     * @test
     */
    public function should_ログイン中ユーザーのブックマークを取得()
    {
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
                'id' => $this->post->id,
                'about' => $this->post->about,
                'post_title' => $this->post->post_title,
                'password_judge' => $this->post->post_password ? true : false,
                'updated_at' => $this->post->updated_at,
                'user' => [
                    'id' => $this->post->user->id,
                    'name' => $this->post->user->name,
                    'url' => '/images/default-image.jpeg',
                ],
                'photos' => [],
                'bookmarked_by_user' => true,
                'my_post' => false,
            ]);
    }
}
