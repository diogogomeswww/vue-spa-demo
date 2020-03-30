<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use App\Models\Subscriber;
use Tests\TestCase;

class FieldTest extends TestCase
{
    /**
     * Each field can have many subscribers
     *
     * @test
     */
    public function each_field_can_have_many_subscribers()
    {
        $field = factory(Field::class)->create();

        $subscriber1 = factory(Subscriber::class)->create();
        $subscriber2 = factory(Subscriber::class)->create();

        $field->subscribers()->attach($subscriber1, ['value' => 'value1']);
        $field->subscribers()->attach($subscriber2, ['value' => 'value1']);

        $this->assertCount(2, $field->subscribers);

        $this->assertTrue($field->subscribers->contains($subscriber1));
        $this->assertTrue($field->subscribers->contains($subscriber2));
    }
}
