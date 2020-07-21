<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserEditApiTest extends TestCase
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
    public function should_ユーザー情報を更新できる()
    {
        $data = [
            'name' => 'test'
        ];

        $response = $this->actingAs($this->user)
            ->json('PUT', route('user.update'), $data);

        $response->assertStatus(200)
            ->assertJson([
                'name' => $data['name']
            ]);
    }

    /**
     * @test
     */
    public function should_ゲストユーザーはユーザー情報を更新できない()
    {
        $user = factory(User::class)->create([
            'login_id' => "guest001",
        ]);

        $data = [
            'name' => 'test'
        ];

        $response = $this->actingAs($user)
            ->json('PUT', route('user.update'), $data);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function should_ログイン中のユーザー情報をid含めて取得できる()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user.show'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'login_id' => $this->user->login_id,
                'name' => $this->user->name,
            ]);
    }

    /**
     * @test
     */
    public function should_ユーザーを削除できる()
    {
        $response = $this->actingAs($this->user)
            ->json('DELETE', route('user.delete'));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }
}
