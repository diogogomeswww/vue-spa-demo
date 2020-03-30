<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddFieldTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $this->assertEquals(0, Field::count());

        $response = $this->post('/api/fields', [
            'title' => 'Company',
            'type' => Field::TYPE_STRING
        ]);

        $response->assertStatus(302);

        $this->assertEquals(0, Field::count());
    }

    /** @test */
    public function it_creates_a_new_field()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Field::count());

        $response = $this->post('/api/fields', [
            'title' => 'Company',
            'type' => Field::TYPE_STRING
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Field::count());
    }

    /** @test */
    public function it_removes_bad_keys()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Field::count());

        $response = $this->post('/api/fields', [
            'title' => 'Company',
            'type' => Field::TYPE_STRING,
            'some_other_key' => 'some_other_value'
        ]);

        $response->assertStatus(201);
        $this->assertEquals(1, Field::count());
    }

    /** @test */
    public function it_validates_the_request()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->assertEquals(0, Field::count());

        $response = $this->post('/api/fields', [
            'title' => '',
            'type' => 'non_existing_type',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Field::count());
    }
}
