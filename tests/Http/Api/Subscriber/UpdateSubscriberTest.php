<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Subscriber;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateSubscriberTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $subscriber = factory(Subscriber::class)->create();

        $response = $this->put("/api/subscribers/{$subscriber->getKey()}", [
            'name' => 'new Name'
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_update_the_subscribers_name_and_email()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscriber = factory(Subscriber::class)->create();

        $response = $this->put("/api/subscribers/{$subscriber->getKey()}", $new = [
            'name' => 'new name',
            'email' => 'new@google.com'
        ]);

        $response->assertStatus(200);

        $subscriber = Subscriber::first();

        $this->assertEquals($new['name'], $subscriber->name);
        $this->assertEquals($new['email'], $subscriber->email);
    }

    /** @test */
    public function it_can_not_update_the_subscribers_state()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscriber = factory(Subscriber::class)->create([
            'state' => Subscriber::STATE_ACTIVE
        ]);

        $response = $this->put("/api/subscribers/{$subscriber->getKey()}", [
            'state' => Subscriber::STATE_BOUNCED
        ]);

        $response->assertStatus(200);

        $this->assertEquals(Subscriber::STATE_ACTIVE, Subscriber::first()->state);
    }

    /** @test */
    public function it_validates_name_and_email()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $subscriber = factory(Subscriber::class)->create([
            'email' => $email = 'valid@google.com'
        ]);

        $response = $this->put("/api/subscribers/{$subscriber->getKey()}", [
            'email' => 'invalid'
        ]);

        $response->assertStatus(422);

        $this->assertEquals($email, Subscriber::first()->email);
    }
}
