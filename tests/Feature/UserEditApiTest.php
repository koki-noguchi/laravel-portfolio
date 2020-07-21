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
}
