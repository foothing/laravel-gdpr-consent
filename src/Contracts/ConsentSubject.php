<?php namespace Foothing\Laravel\Consent\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface ConsentSubject {

    /**
     * Returns the subject id.
     *
     * @return mixed
     */
    public function getSubjectId();

    /**
     * Relation to the consent table.
     *
     * @return HasMany
     */
    public function consents();
}
