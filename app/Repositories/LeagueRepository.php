<?php

namespace App\Repositories;

use App\Models\League;
use App\Repositories\EloquentRepository;

class LeagueRepository extends EloquentRepository
{
    public function __construct(League $league)
    {
        parent::__construct($league);
    }
}
