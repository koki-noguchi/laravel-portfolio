<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyPostListApiTest extends TestCase
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
    public function should_自分の募集履歴をJSON形式で取得()
    {
        factory(Post::class, 5)->create();

        $posts = Post::with(['user'])->orderBy('created_at', 'desc')->get();

        $expected_data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'about' => $post->about,
                'post_title' => $post->post_title,
                'password_judge' => $post->post_password ? true : false,
                'user' => [
                    'name' => $post->user->name,
                ],
            ];
        })
        ->all();

        $data = [
            'post_title' => 'postsample',
            'post_password' => 'sample',
            'min_number' => '1',
            'max_number' => '5'
        ];
        $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response = $this->actingAs($this->user)
            ->json('GET', route('history.index'));

        $response->assertStatus(200)
            ->assertJsonFragment(['post_title' => $data['post_title']])
            ->assertJsonMissing([
                "data" => $expected_data,
            ]);
    }
}
