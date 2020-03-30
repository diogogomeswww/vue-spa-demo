<?php

namespace App\Rules;

use App\Models\Field;
use Illuminate\Contracts\Validation\Rule;

class FieldsRule implements Rule
{
    /** @var \Illuminate\Support\Collection */
    protected $missing_ids;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $fields
     * @return bool
     */
    public function passes($attribute, $fields = [])
    {
        if (count($fields) == 0) {
            return true;
        }

        $existing_ids = Field::whereIn('id', array_keys($fields))->pluck('id');

        $this->missing_ids = collect(array_keys($fields))->diff($existing_ids);

        return $this->missing_ids->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->missing_ids->count() == 1
            ? "Field with ID {$this->missing_ids->implode(',')} not found."
            : "Fields with IDs {$this->missing_ids->implode(',')} not found.";
    }
}
