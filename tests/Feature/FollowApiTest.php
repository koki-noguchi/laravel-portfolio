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

    /**
     * @test
     */
    public function should_フォロー・フォロワー一覧を取得できる()
    {
        $this->actingAs($this->user)
            ->json('PUT', route('follow.add', [
                'id' => $this->follow_user->id,
            ]));

        $this->actingAs($this->follow_user)
        ->json('PUT', route('follow.add', [
            'id' => $this->user->id,
        ]));

        $response = $this->actingAs($this->user)
            ->json('GET', route('follow.show', [
                'id' => $this->user->id,
            ]));

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'url' => '/images/default-image.jpeg',
                    'followed_judge' => false,
                    'follow_count' => 1,
                    'follower_count' => 1,
                    'followings' => [[
                        'id' => $this->follow_user->id,
                        'name' => $this->follow_user->name,
                        'url' => '/images/default-image.jpeg',
                        'followed_judge' => true,
                        'follow_count' => 1,
                        'follower_count' => 1,
                    ]],
                    'followers' => [[
                        'id' => $this->follow_user->id,
                        'name' => $this->follow_user->name,
                        'url' => '/images/default-image.jpeg',
                        'followed_judge' => true,
                        'follow_count' => 1,
                        'follower_count' => 1,
                    ]]
                ]);
    }
}
