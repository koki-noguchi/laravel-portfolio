<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_新しいユーザーの作成()
    {
        $data = [
            'name' => 'laravel user',
            'login_id' => 'test1111',
            'password' => 'test1222',
            'password_confirmation' => 'test1222',
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }
}
