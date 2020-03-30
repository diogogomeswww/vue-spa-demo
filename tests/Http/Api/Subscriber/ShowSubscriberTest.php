<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Subscriber;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowSubscriberTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $subscriber = factory(Subscriber::class)->create();

        $response = $this->get("/api/subscribers/{$subscriber->getKey()}");

        $response->assertStatus(302);
    }

    /** @test */
    public function it_gets_a_Subscriber()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscriber = factory(Subscriber::class)->create();

        $response = $this->get("/api/subscribers/{$subscriber->getKey()}");

        $response->assertStatus(200)
            ->assertExactJson($subscriber->loadMissing('fields')->toArray());
    }

    /** @test */
    public function it_handles_a_not_found_subscriber()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $response = $this->get("/api/subscribers/1");

        $response->assertStatus(404)->assertExactJson([]);
    }
}
