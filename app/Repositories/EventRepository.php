<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\EloquentRepository;

class EventRepository extends EloquentRepository
{
    public function __construct(Event $event)
    {
        parent::__construct($event);
    }

    public function getEventsByMatch($matchId)
    {
        return $this->where('match_id', $matchId)->lists('id');
    }

    public function deleteEventsByMatch($matchId)
    {
        return $this->where('match_id', $matchId)->deleteAll();
    }
}
