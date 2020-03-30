<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Subscriber;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetSubscriberTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $this->get('/api/subscribers')->assertStatus(302);
    }

    /** @test */
    public function it_gets_a_list_of_Subscribers()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscribers = factory(Subscriber::class, 2)->create();

        $this->get('/api/subscribers')
            ->assertStatus(200)
            ->assertExactJson($subscribers->loadMissing('fields')->toArray());
    }
}
