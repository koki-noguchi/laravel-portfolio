<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->follow_user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_フォローできる()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', route('follow.add', [
                'id' => $this->follow_user->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'followee_id' => $this->follow_user->id,
            ]);

        $this->assertEquals(1, $this->user->followings()->count());
    }

    /**
     * @test
     */
    public function should_同じユーザーを2回フォローできない()
    {
        $param = ['id' => $this->follow_user->id];
        $this->actingAs($this->user)->json('PUT', route('follow.add', $param));
        $this->actingAs($this->user)->json('PUT', route('follow.add', $param));

        $this->assertEquals(1, $this->user->followings()->count());
    }

    /**
     * @test
     */
    public function should_フォローを外せる()
    {
        $this->user->followings()->attach($this->follow_user->id,);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('follow.delete', [
                'id' => $this->follow_user->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'followee_id' => $this->follow_user->id,
            ]);

        $this->assertEquals(0, $this->user->followings()->count());
    }
}
