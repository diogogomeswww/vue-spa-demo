<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Subscriber;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroySubscriberTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $subscriber = factory(Subscriber::class)->create();

        $response = $this->delete("/api/subscribers/{$subscriber->getKey()}");

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_delete_the_subscriber()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscriber = factory(Subscriber::class)->create();

        $this->assertEquals(1, Subscriber::count());

        $response = $this->delete("/api/subscribers/{$subscriber->getKey()}");

        $response->assertStatus(200);

        $this->assertEquals(0, Subscriber::count());
    }

    /** @test */
    public function it_handles_not_found_subscriber()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->delete("/api/subscribers/1")->assertStatus(404);
    }
}
