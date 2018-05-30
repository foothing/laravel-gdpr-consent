<?php namespace Foothing\Laravel\Consent\Traits;

use Foothing\Laravel\Consent\Models\Consent;

trait HasConsents {

    public function consents()
    {
        return $this->hasMany(Consent::class, 'subject_id');
    }

}
