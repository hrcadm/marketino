<?php

namespace App\Models\Traits;

trait DoNotAllowUpdate
{

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            throw new \RuntimeException('Update is not allowed for ' . $model->name . ' model');
        });
        static::updated(function ($model) {
            $model->timestamps = true;
        });
    }
}
