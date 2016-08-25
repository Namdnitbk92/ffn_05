<?php

namespace App\Repositories;

use App\Models\UserMatch;
use App\Repositories\EloquentRepository;

class UserMatchRepository extends EloquentRepository
{
    public function __construct(UserMatch $userMatch)
    {
        parent::__construct($userMatch);
    }

    public function createOrUpdateBet($matchId, $userId, $dataBet)
    {
        $userbet = $this->where('match_id', $matchId)->where('user_id', $userId)->all();
        if (!$dataBet) {
            $dataBet['team_guess'] = $dataBet['teamGuess'];
        }

        if (!$userbet && count($userbet) > 0) {
            $userbet = $userbet->first();
            if ($userbet != null) {
                $this->update($dataBet, $userbet->id);
            } else {
                $this->create($dataBet);
            }
        }
    }
}
