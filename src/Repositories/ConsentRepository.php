<?php namespace Foothing\Laravel\Consent\Repositories;

use Foothing\Laravel\Consent\Models\Consent;
use Foothing\Repository\Eloquent\EloquentRepository;

class ConsentRepository extends EloquentRepository {

    public function __construct(Consent $consent) {
        parent::__construct($consent);
    }

    public function findConsentBySubjectAndTreatment($subjectId, $treatmentId) {
        return $this->model
            ->whereSubjectId($subjectId)
            ->whereTreatmentId($treatmentId)
            ->first();
    }

}
