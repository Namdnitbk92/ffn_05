<?php

namespace App\Repositories;

use App\Repositories\EloquentRepository;
use App\Models\Match;
use App\Repositories\EventRepository;
use DB;
use Exception;

class MatchRepository extends EloquentRepository
{
    protected $eventRepository;

    public function __construct(Match $match, EventRepository $eventRepository)
    {
        parent::construct($match);
        $this->$eventRepository = $eventRepository;
    }

    public function updateMatch($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $events = $data['events_data'];
            $match = $this->find($id);
            if ($match) {
                $this->eventRepository->deleteEventsByMatch($id);
                if ($events) {
                    $events = json_decode($events);
                    $eventsArr = collect([]);
                    foreach ($events as $event) {
                        $temp = [];
                        if ($event->content != null && $event->content != "") {
                            $temp['content'] = $event->content;
                        }
                        if ($event->time != null && $event->time != "") {
                            $temp['time'] = $event->time;
                        }
                        $temp['match_id'] = $match->id;
                        $eventsArr->push($temp);
                    }
                    if (!$eventsArr->isEmpty()) {
                        $this->eventRepository->create($eventsArr);
                    }
                }
                if ($data['home_goal'] && $data['guest_goal']) {
                    $data['result'] = $data['home_goal'] . ' - ' . $data['guest_goal'];
                }
                $this->update($data);
            }
        });
    }

    public function deleteByMatch($id)
    {
        if (!$id) {
            return false;
        }
        
        DB::transaction(function () use ($id) {
            try {
                $this->eventRepository->deleteEventsByMatch($id);
                $this->delete($id);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        });

        return true;
    }
}
