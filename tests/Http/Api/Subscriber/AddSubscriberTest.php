<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\Models\Subscriber;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddSubscriberTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $this->assertEquals(0, Subscriber::count());

        $response = $this->post('/api/subscribers', []);

        $response->assertStatus(302);

        $this->assertEquals(0, Subscriber::count());
    }

    /** @test */
    public function it_creates_a_new_subscriber()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Subscriber::count());

        $response = $this->post('/api/subscribers', [
            'email' => 'john@example.com',
            'name' => 'John Doe'
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Subscriber::count());
    }

    /** @test */
    public function it_creates_a_new_subscriber_with_fields()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Subscriber::count());
        $this->assertEquals(0, Field::count());

        $field1 = factory(Field::class)->create();
        $field2 = factory(Field::class)->create([
            'type' => Field::TYPE_DATE
        ]);

        $response = $this->post('/api/subscribers', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'fields' => $fields = [
                $field1->getKey() => 'field1',
                $field2->getKey() => now()
            ]
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Subscriber::count());

        $subscriber = Subscriber::first();
        $this->assertCount(2, $subscriber->fields);

        foreach($fields as $id => $value) {
            $this->assertEquals($value, $subscriber->fields->firstWhere('id', $id)->pivot->value);
        }
    }

    /** @test */
    public function it_validates_the_request()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Subscriber::count());

        $this->post('/api/subscribers', [])->assertStatus(422);

        $this->post('/api/subscribers', [
            'email' => 'bademail'
        ])->assertStatus(422);

        $this->assertEquals(0, Subscriber::count());
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        factory(Subscriber::class)->create([
            'email' => $email = 'email@google.com'
        ]);

        $this->post('/api/subscribers', [
            'email' => $email
        ])->assertStatus(422);

        $this->assertEquals(1, Subscriber::count());
    }
}
