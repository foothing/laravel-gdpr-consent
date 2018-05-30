<?php namespace Foothing\Laravel\Consent\Repositories;

use Foothing\Laravel\Consent\Models\Event;
use Foothing\Repository\Eloquent\EloquentRepository;

class EventRepository extends EloquentRepository
{

    public function __construct(Event $event)
    {
        parent::__construct($event);
    }

}
