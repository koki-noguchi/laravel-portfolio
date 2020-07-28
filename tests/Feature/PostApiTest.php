<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
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
            'max_number' => '5'
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $post = Post::first();

        $response
            ->assertStatus(201)
            ->assertJson(['post_title' => $post->post_title]);
    }

    /**
     * @test
     */
    public function should_メッセージ募集を削除できる()
    {
        factory(Post::class)->create();
        $this->post = Post::first();

        $response = $this->actingAs($this->user)
            ->json('DELETE',route('post.delete', [
                'id' => $this->post->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', [
            'id' => $this->post->id,
        ]);
    }

    /**
     * @test
     */
    public function should_メッセージ募集を更新できる()
    {
        factory(Post::class)->create();
        $this->post = Post::first();

        $data = [
            'post_title' => 'sample',
            'about' => 'samplesample',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('post.update', [
                'id' => $this->post->id,
            ]),$data);

        $response->assertStatus(200)
            ->assertJson(['post_title' => $data['post_title']]);
    }

    /**
     * @test
     */
    public function should_写真をアップロードできる()
    {
        Storage::fake('s3');

        $data = [
            'post_title' => 'postsample',
            'post_password' => 'sample',
            'max_number' => '5',
            'post_photo' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response->assertStatus(201);
        $photo = Photo::first();

        Storage::cloud()->assertExists($photo->post_photo);
    }

    /**
     * @test
     */
    public function should_データベースにエラーがある場合は保存しない()
    {
        Schema::drop('photos');

        Storage::fake('s3');

        $data = [
            'post_title' => 'postsample',
            'post_password' => 'sample',
            'max_number' => '5',
            'post_photo' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response->assertStatus(500);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function should_ファイル保存エラーの場合はDBに保存しない()
    {
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $data = [
            'post_title' => 'postsample',
            'post_password' => 'sample',
            'max_number' => '5',
            'post_photo' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response->assertStatus(500);

        $this->assertEmpty(Photo::all());
    }
}
