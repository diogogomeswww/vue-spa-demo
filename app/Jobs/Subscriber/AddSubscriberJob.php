<?php


namespace App\Jobs\Subscriber;


use App\Jobs\AbstractJob;
use App\Models\Field;
use App\Models\Subscriber;
use App\Rules\EmailHostIsActiveRule;
use App\Rules\FieldsRule;
use Arr;
use Illuminate\Support\MessageBag;

class AddSubscriberJob extends AbstractJob
{
    /** @var array */
    protected $data;

    /** @var \Illuminate\Contracts\Support\MessageBag */
    protected $validation;

    /** @var Subscriber */
    protected $subscriber;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;

        $this->validation = new MessageBag();
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        if (! $validated = $this->validate()) {
            return $this;
        }

        /** Subscriber */
        $this->subscriber = Subscriber::create(
            Arr::only($validated, ['name', 'email'])
        );

        foreach ($validated['fields'] ?? [] as $field_id => $fields_value) {
            $this->subscriber->fields()->attach(Field::find($field_id), [
                'value' => $fields_value
            ]);
        }

        return $this;
    }

    /**
     * @return array|bool
     */
    protected function validate()
    {
        $validator = validator()->make($this->data, [
            'email' => ['bail', 'required', 'email', 'unique:subscribers', new EmailHostIsActiveRule],
            'name' => 'required',
            'fields' => ['nullable', new FieldsRule],
        ]);

        if ($validator->fails()) {
            $this->validation = $validator->getMessageBag();

            return false;
        }

        return $validator->validated();
    }

    /**
     * @return \App\Models\Subscriber
     */
    public function getSubscriber(): \App\Models\Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function getValidation()
    {
        return $this->validation;
    }
}
