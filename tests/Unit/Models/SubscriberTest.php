<?php

namespace Tests\Unit\Models;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    /**
     * @covers \App\Models\Subscriber::fields
     * @test
     */
    public function each_subscriber_can_have_many_fields()
    {
        $subscriber = factory(Subscriber::class)->create();

        $field1 = factory(Field::class)->create();
        $field2 = factory(Field::class)->create();

        $subscriber->fields()->attach($field1, ['value' => 'value1']);
        $subscriber->fields()->attach($field2, ['value' => 'value2']);

        $this->assertCount(2, $subscriber->fields);

        $this->assertTrue($subscriber->fields->contains($field1));
        $this->assertTrue($subscriber->fields->contains($field2));

        $this->assertInstanceOf(BelongsToMany::class, $subscriber->fields());
    }

    /**
     * @covers \App\Models\Subscriber::syncField
     * @test
     */
    public function it_has_an_helper_method_to_add_a_new_field()
    {
        $subscriber = factory(Subscriber::class)->create();

        $field1 = factory(Field::class)->create([
            'type' => Field::TYPE_STRING
        ]);

        $field2 = factory(Field::class)->create([
            'type' => Field::TYPE_DATE
        ]);

        // Passing field model
        $subscriber->syncField($field1, 'value1');

        // Passing field_id
        $subscriber->syncField($field2->getKey(), now());

        $this->assertCount(2, $subscriber->fields);
        $this->assertTrue($subscriber->fields->contains($field1));
        $this->assertTrue($subscriber->fields->contains($field2));
    }
}
