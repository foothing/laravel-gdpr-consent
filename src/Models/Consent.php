<?php namespace Foothing\Laravel\Consent\Models;

use Foothing\Laravel\Consent\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Consent extends Model
{
    use Uuids;

    protected $table = 'gdpr_consent';

    public $incrementing = false;

    public $fillable = ['subject_id', 'treatment_id'];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
