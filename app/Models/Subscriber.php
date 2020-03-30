<?php

namespace App\Models;

use DB;
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
        $subscriber_id = $this->getKey();

        if (empty($value)) {
            $this->fields()->detach($field_id);
        } else {
            DB::table('field_subscriber')
                ->updateOrInsert(compact('field_id', 'subscriber_id'), compact('value'));
        }
    }
}
