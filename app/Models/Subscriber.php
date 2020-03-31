<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /** @var array */
    protected $guarded = ['id'];

    public const STATE_UNCONFIRMED = 'unconfirmed';
    public const STATE_ACTIVE = 'active';
    public const STATE_UNSUBSCRIBED = 'unsubscribed';
    public const STATE_JUNK = 'junk';
    public const STATE_BOUNCED = 'bounced';

    /** @var array */
    public const STATES = [
        self::STATE_UNCONFIRMED,
        self::STATE_ACTIVE,
        self::STATE_UNSUBSCRIBED,
        self::STATE_JUNK,
        self::STATE_BOUNCED,
    ];

    /** @var array */
    protected $with = ['fields'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->belongsToMany(Field::class)
            ->withPivot('value', 'created_at', 'updated_at');
    }

    /**
     * @param integer|Field $field
     * @param mixed $value
     */
    public function syncField($field, $value)
    {
        $field_id = is_int($field) ? $field : $field->getKey();

        if (empty($value)) {
            $this->fields()->detach($field_id);
        } else {
            // prevent duplicates
            if ($this->fields()->where(compact('field_id'))->exists()) {
                $this->fields()->updateExistingPivot($field_id, compact('value'));
            } else {
                $this->fields()->attach($field_id, compact('value'));
            }
        }
    }
}
