<?php

namespace Tests\Feature\Services\Subscriber;

use App\Jobs\Subscriber\AddSubscriberJob;
use App\Models\Field;
use App\Models\Subscriber;
use Tests\TestCase;

class AddSubscriberJobTest extends TestCase
{
    /** @test */
    public function it_adds_a_subscriber_with_no_extra_fields()
    {
        $subscriber = [
            'email' => $email = 'john@example.com',
            'name' => 'John Doe'
        ];

        $this->assertCount(0, Subscriber::all());

        AddSubscriberJob::dispatch($subscriber);

        $this->assertCount(1, Subscriber::all());
        $this->assertCount(0, Field::all());

        $this->assertEquals($email, Subscriber::first()->email);
    }

    /** @test */
    public function it_adds_a_subscriber_with_extra_fields()
    {
        $field = factory(Field::class)->create([
            'title' => 'note',
            'type' => Field::TYPE_STRING
        ]);

        $this->assertCount(0, Subscriber::all());

        AddSubscriberJob::dispatch([
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'fields' => [
                $field->getKey() => $note = 'some note'
            ]
        ]);

        $this->assertCount(1, Subscriber::all());
        $subscriber = Subscriber::first();
        $this->assertCount(1, $subscriber->fields);
        $this->assertEquals($note, $subscriber->fields->first()->pivot->value);
    }

    /**
     * Email must be in valid format and host domain must be active.
     *
     * @test
     */
    public function it_validates_the_subscribers_email()
    {
        /** @var AddSubscriberJob $cmd */
        $cmd = AddSubscriberJob::dispatchNow([
            'name' => 'John Doe',
            'email' => 'valid@google.com'
        ]);
        $this->assertEquals(0, $cmd->getValidation()->count());

        $cmd = AddSubscriberJob::dispatchNow([
            'name' => 'John Doe',
            'email' => ''
        ]);
        $this->assertEquals(1, $cmd->getValidation()->count());
        $this->assertEquals('The email field is required.', $cmd->getValidation()->first('email'));

        $cmd = AddSubscriberJob::dispatchNow([
            'name' => 'John Doe',
            'email' => 'johndoe'
        ]);
        $this->assertEquals(1, $cmd->getValidation()->count());
        $this->assertEquals('The email must be a valid email address.', $cmd->getValidation()->first('email'));

        $cmd = AddSubscriberJob::dispatchNow([
            'name' => 'John Doe',
            'email' => 'johndoe@abcdefghjlmnopq.com'
        ]);
        $this->assertEquals(1, $cmd->getValidation()->count());
        $this->assertEquals('The email must be a valid email address.', $cmd->getValidation()->first('email'));
    }

    /**
     * Name is required
     *
     * @test
     */
    public function it_validates_the_subscribers_name()
    {
        $cmd = AddSubscriberJob::dispatchNow([
            'name' => '',
            'email' => 'valid@google.com'
        ]);
        $this->assertEquals(1, $cmd->getValidation()->count());
        $this->assertEquals('The name field is required.', $cmd->getValidation()->first('name'));
    }

    /** @test */
    public function it_validates_non_existing_fields()
    {
        $this->assertCount(0, Subscriber::all());

        $cmd = AddSubscriberJob::dispatchNow([
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'fields' => [
              999999 => 'non_existing_field'
            ]
        ]);

        $this->assertCount(0, Subscriber::all());
        $this->assertCount(0, Field::all());

        $this->assertEquals(1, $cmd->getValidation()->count());
        $this->assertEquals('Field with ID 999999 not found.', $cmd->getValidation()->first('fields'));
    }
}
