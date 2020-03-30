<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowFieldTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $field = factory(Field::class)->create();

        $response = $this->get("/api/fields/{$field->getKey()}");

        $response->assertStatus(302);
    }

    /** @test */
    public function it_gets_a_field()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $field = factory(Field::class)->create();

        $response = $this->get("/api/fields/{$field->getKey()}");

        $response->assertStatus(200)
            ->assertExactJson($field->toArray());
    }

    /** @test */
    public function it_handles_a_not_found_field()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $response = $this->get("/api/fields/1");

        $response->assertStatus(404)->assertExactJson([]);
    }
}
