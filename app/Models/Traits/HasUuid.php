<?php

namespace App\Models\Traits;

use App\Models\UuidModel;
use App\Models\UuidPivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasUuid
 *
 * @package App\Models\Traits
 * @property string $id
 * @property-read bool $isLockedUuid
 */
trait HasUuid
{
    protected $isLockedUuid = true;

    /**
     * Used by Eloquent to get primary key type.
     * UUID Identified as a string.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Used by Eloquent to get if the primary key is auto increment value.
     * UUID is not.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Add behavior to creating and saving Eloquent events.
     *
     * @return void
     */
    public static function bootHasUuid()
    {
        // Create a UUID if model has empty ID
        static::creating(
            function (Model $model) {
                /* @var UuidModel|UuidPivot $model */
                $model->keyType = 'string';
                $model->incrementing = false;

                if (!$model->getKey()) {
                    $model->{$model->getKeyName()} = (string)Str::uuid();
                }
            }
        );

        // Revert to original UUID if someone is trying to change it on update/save
        static::saving(
            function (Model $model) {
                /* @var UuidModel|UuidPivot $model */
                $original_id = $model->getOriginal('id');
                /* @phpstan-ignore-next-line */
                if (!is_null($original_id) && $model->isLockedUuid) {
                    /* @phpstan-ignore-next-line */
                    if ($original_id !== $model->id) {
                        /* @phpstan-ignore-next-line */
                        $model->id = $original_id;
                    }
                }
            }
        );
    }
}
