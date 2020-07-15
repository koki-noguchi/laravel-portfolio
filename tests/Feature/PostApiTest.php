<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostSubmitApiTest extends TestCase
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
    public function should_メッセージを募集できる()
    {
        $data = [
            'post_title' => 'postsample',
            'post_password' => 'sample',
            'min_number' => '1',
            'max_number' => '5'
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $post = Post::first();

        $response
            ->assertStatus(201)
            ->assertJson(['post_title' => $post->post_title]);
    }
}
