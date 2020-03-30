<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateFieldTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $field = factory(Field::class)->create();

        $response = $this->put("/api/fields/{$field->getKey()}", [
            'title' => 'new Title'
        ]);

        $response->assertStatus(302);
    }

    /** @test */
    public function it_can_update_the_fields_title()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $field = factory(Field::class)->create();

        $response = $this->put("/api/fields/{$field->getKey()}", [
            'title' => 'new Title'
        ]);

        $response->assertStatus(200);

        $this->assertEquals('new Title', Field::first()->title);
    }

    /** @test */
    public function it_can_not_update_the_fields_type()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $field = factory(Field::class)->create([
            'type' => Field::TYPE_STRING
        ]);

        $response = $this->put("/api/fields/{$field->getKey()}", [
            'title' => 'some title',
            'type' => Field::TYPE_DATE
        ]);

        $this->assertEquals(200, $response->getStatusCode(), $response->content());

        $this->assertEquals(Field::TYPE_STRING, Field::first()->type);
    }
}
