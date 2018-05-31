<?php namespace Foothing\Laravel\Consent\Core;

use Foothing\Laravel\Consent\Contracts\ConsentSubject;
use Foothing\Laravel\Consent\Models\Consent;
use Foothing\Laravel\Consent\Models\Event;
use Foothing\Laravel\Consent\Models\Treatment;
use Foothing\Laravel\Consent\Repositories\ConsentRepository;
use Illuminate\Support\Facades\DB;

class ConsentManager {

    /**
     * @var \Foothing\Laravel\Consent\Repositories\ConsentRepository
     */
    protected $consents;

    /**
     * @var EventManager
     */
    protected $events;

    public function __construct(ConsentRepository $consents, EventManager $events)
    {
        $this->consents = $consents;

        $this->events = $events;
    }

    public function grantConsent(Treatment $treatment, ConsentSubject $subject, $payload = [])
    {
        $consent = $this->getSubjectConsentByTreatment($subject, $treatment);

        $event = new Event([
            'name' => 'consent.grant',
            'consent_id' => $consent->id,
            'subject_id' => $subject->getSubjectid(),
            'payload' => $payload,
        ]);

        DB::transaction(function() use ($consent, $event){
            $this->grantConsentTransaction($consent, $event);
        });

        return $consent;
    }

    /**
     * Wraps the grant transaction.
     *
     * @param Consent $consent
     * @param Event   $event
     */
    public function grantConsentTransaction(Consent $consent, Event $event)
    {
        $this->consents->update($consent);

        $this->events->log($event);
    }

    /**
     * If a record already exists it will be loaded,
     * otherwise a new instance is returned.
     *
     * @param ConsentSubject $subject
     * @param Treatment      $treatment
     *
     * @return Consent
     */
    public function getSubjectConsentByTreatment(ConsentSubject $subject, Treatment $treatment)
    {
        $consent = $this->consents->findConsentBySubjectAndTreatment(
            $subject->getSubjectId(),
            $treatment->id
        );

        if (! $consent)
        {
            return new Consent([
                'subject_id' => $subject->getSubjectId(),
                'treatment_id' => $treatment->id
            ]);
        }

        return $consent;
    }
}