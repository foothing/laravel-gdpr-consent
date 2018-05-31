<?php namespace Foothing\Laravel\Consent\Facades;

use Foothing\Laravel\Consent\ConsentApi;
use Illuminate\Support\Facades\Facade;

class Consent extends Facade
{

    protected static function getFacadeAccessor()
    {
        return ConsentApi::class;
    }

}
