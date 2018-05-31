<?php namespace Foothing\Laravel\Consent\Traits;

use Webpatser\Uuid\Uuid;

trait Uuids {

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}
