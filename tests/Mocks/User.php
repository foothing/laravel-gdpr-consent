<?php namespace tests\Foothing\Laravel\Consent\Mocks;

use Foothing\Laravel\Consent\Contracts\ConsentSubject;

class User implements ConsentSubject
{
    public function getSubjectid() {
        return 1;
    }

    public function consents() {
        return null;
    }
}