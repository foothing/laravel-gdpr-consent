<?php namespace Foothing\Laravel\Consent\Core;

use Foothing\Laravel\Consent\Models\Event;
use Foothing\Laravel\Consent\Repositories\EventRepository;

class EventManager
{

    /**
     * @var \Foothing\Laravel\Consent\Repositories\EventRepository
     */
    protected $events;

    public function __construct(EventRepository $events)
    {
        $this->events = $events;
    }

    public function log(Event $event)
    {
        return $this->events->create($event);
    }

}
