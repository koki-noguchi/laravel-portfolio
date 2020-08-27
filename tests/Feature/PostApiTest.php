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

class PostApiTest extends TestCase
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
            'about' => 'about',
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
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);

        $this->post = Post::first();

        $response = $this->actingAs($this->user)
            ->json('DELETE',route('post.delete', [
                'post' => $this->post->id,
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
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);
        $this->post = Post::first();

        $data = [
            'post_title' => 'sample',
            'about' => 'samplesample',
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('post.update', [
                'post' => $this->post->id,
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
            'about' => 'about',
            'max_number' => '5',
            'post_photo' => array(UploadedFile::fake()->image('photo.jpg'), UploadedFile::fake()->image('photos.jpg')),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response->assertStatus(201);
        $photo = Photo::first();

        Storage::cloud()->assertExists($photo->post_photo);
    }

//     /**
//      * @test
//      */
//     public function should_データベースにエラーがある場合は保存しない()
//     {
//         Schema::drop('photos');

//         Storage::fake('s3');

//         $data = [
//             'post_title' => 'postsample',
//             'post_password' => 'sample',
//             'about' => 'about',
//             'max_number' => '5',
//             'post_photo' => array(UploadedFile::fake()->image('photo.jpg')),
//         ];

//         $response = $this->actingAs($this->user)
//             ->json('POST', route('posting.create'), $data);

//         $response->assertStatus(500);

//         $this->assertEquals(0, count(Storage::cloud()->files()));
//     }

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
            'about' => 'about',
            'max_number' => '5',
            'post_photo' => array(UploadedFile::fake()->image('photo.jpg')),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('posting.create'), $data);

        $response->assertStatus(500);

        $this->assertEmpty(Photo::all());
    }

    /**
     * @test
     */
    public function should_写真を削除できる()
    {
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);
        $post = Post::first();

        Storage::fake('s3');

        $data = [
            'post_photo' => array(UploadedFile::fake()->image('photo.jpg'), UploadedFile::fake()->image('photos.jpg')),
        ];

        $this->actingAs($this->user)
            ->json('POST', route('photo.create',[
                'post' => $post->id,
            ]), $data);

        $photo = Photo::first();

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('photo.delete', [
                'photo' => $photo->id,
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('photos', [
            'id' => $photo->id,
        ]);

        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function should_写真のみ取得してアップロードできる()
    {
        factory(Post::class)->create([
            'user_id' => $this->user->id,
        ]);
        $post = Post::first();

        Storage::fake('s3');

        $data = [
            'post_photo' => array(UploadedFile::fake()->image('photo.jpg'), UploadedFile::fake()->image('photos.jpg')),
        ];

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create',[
                'post' => $post->id,
            ]), $data);

        $response->assertStatus(201);
        $photo = Photo::first();

        Storage::cloud()->assertExists($photo->post_photo);
    }
}
