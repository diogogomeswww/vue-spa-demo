<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyFieldTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $field = factory(Field::class)->create();

        $response = $this->delete("/api/fields/{$field->getKey()}");

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_delete_the_field()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $field = factory(Field::class)->create();

        $this->assertEquals(1, Field::count());

        $response = $this->delete("/api/fields/{$field->getKey()}");

        $response->assertStatus(200);

        $this->assertEquals(0, Field::count());
    }

    /** @test */
    public function it_handles_not_found_field()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $this->delete("/api/fields/1")->assertStatus(404);
    }
}
