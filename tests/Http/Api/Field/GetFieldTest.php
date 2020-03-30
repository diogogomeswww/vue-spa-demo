<?php

namespace Tests\Http\Api\Subscriber;

use App\Models\Field;
use App\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetFieldTest extends TestCase
{
    /** @test */
    public function it_validates_auth_credentials()
    {
        $this->get('/api/fields')->assertStatus(302);
    }

    /** @test */
    public function it_gets_a_list_of_fields()
    {
        Sanctum::actingAs(factory(User::class)->create(), ['*']);

        $fields = factory(Field::class, 2)->create();

        $this->get('/api/fields')
            ->assertStatus(200)
            ->assertExactJson($fields->toArray());
    }
}
