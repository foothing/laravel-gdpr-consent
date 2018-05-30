<?php namespace Foothing\Laravel\Consent\Models;

use Foothing\Laravel\Consent\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model {

    use Uuids;

    protected $table = 'gdpr_treatment';

    public $incrementing = false;
}