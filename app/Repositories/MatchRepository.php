<?php

namespace App\Repositories;

use App\Repositories\TeamRepository;
use App\Repositories\EventRipository;
use App\Repositories\NotificationRipository;
use App\Repositories\UserMatchRipository;
use LRedis;
use DB;

class MatchRepository extends EloquentRepository
{

    public $teamRepository;
    public $eventRipository;
    public $notificationRipository;
    public $userMatchRipository;

    public function __construct(
        TeamRepository $teamRepository,
        EventRipository $eventRipository,
        Notification $notificationRipository,
        UserMatchRipository $userMatchRipository
    ) {
        $this->teamRepository = $teamRepository;
        $this->eventRipository = $eventRipository;
        $this->notificationRipository = $notificationRipository;
        $this->userMatchRipository = $userMatchRipository;
    }

    public function updateMatch($data, $id)
    {
        $redis = LRedis::connection();
        $redis->publish('message', trans('common.admin_send_notifications'));
    }

    public function createOrUpdateBet($dataBet)
    {
        $flag = true;
        DB::transaction(function () use ($dataBet) {
            try {
                if (!$dataBet) {
                    return false;
                }

                $user = auth()->user();
                $matchId = $dataBet['matchId'];
                $this->userMatchRipository->createOrUpdateBet($matchId, $user->id, $dataBet);
                
                $match = $this->find($matchId);
                $home = collect([]);
                $guest = collect([]);

                if (!is_null($match)) {
                    $home = $this->teamRepository->find($match->home_id);
                    $guest = $this->teamRepository->find($match->guest_id);
                } else {
                    $home->push(['name' => '']);
                    $guest->push(['name' => '']);
                }

                $message = 'User ' . $user->name . 'has bet a match' . $home->name . ' - ' . $guest->name . ' [' . $matchId . ']'

                $this->createNotification(
                    $user->id, trans('config.notification.status.created'),
                    $matchId, Match::class,
                    $message
                );
            } catch (Exception $e) {
                $flag = false;
            }
        });

        return $flag;
    }

    public function createNotification(
        $userId, 
        $status, 
        $target_id, 
        $target_class, 
        $message
    ) {
        return $this->notificationRipository->create([
            'user_id' => $userId,
            'status' => $status,
            'target_id' => $target_id,
            'target_class' => $target_class,
            'message' => $message,
        ]);
    }
}
