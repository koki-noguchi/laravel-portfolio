<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserPhotoApiTest extends TestCase
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
    public function should_プロフィール画像をアップロードできる()
    {
        Storage::fake('s3');

        $data = [
            'user_image' => 'data:image/jpg;base64,'. base64_encode(UploadedFile::fake()->image('photo.jpg')) ,
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('user.update'), $data);

        $response->assertStatus(200);

        $user = User::first();
        Storage::cloud()->assertExists($user->user_image);
    }


    /**
     * @test
     */
    public function should_データベースにエラーがある場合は保存しない()
    {
        Schema::drop('users');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('PUT', route('user.update'), [
                'user_image' => 'data:image/jpg;base64,'. base64_encode(UploadedFile::fake()->image('photo.jpg')),
            ]);

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
            'user_image' => 'data:image/jpg;base64,'. base64_encode(UploadedFile::fake()->image('photo.jpg')),
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('user.update'), $data);

        $response->assertStatus(500);

        $this->assertDatabaseMissing('users', [
            'user_image' => $data['user_image']
        ]);
    }
}
