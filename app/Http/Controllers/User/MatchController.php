<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\MatchRepository;
use LRedis;

class MatchController extends Controller
{
    protected $matchRepository;
    protected $teamRepository;

    public function __construct(
        MatchRepository $matchRepository, 
        TeamRepository $teamRepository
    ) {
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matches = [];
        $datafields = [
            'id' => trans('match.id'),
            'home_id' => trans('match.home_team'),
            'guest_id' => trans('match.guess_team'),
            'league_season_id' => trans('match.league_season'),
            'result' => trans('match.result'),
            'rate' => trans('match.rate'),
            'location' => trans('match.location'),
            'start' => trans('match.start'),
            'end' => trans('match.end'),
        ];

        if ($request->ajax()) {
            $matches = $this->matchRepository->all();
            $teams = $this->teamRepository->all();
            $flag = $request->input('sendNotification');
            $resultBet = true;
            if ($flag) {
                $dataBet = $request->all();
                $user = auth()->user();
                $resultBet = $this->matchRepository->createOrUpdateBet($dataBet);
                if ($resultBet) {
                    $redis = LRedis::connection();
                    $data = app()->make('stdClass');
                    $data->user_id = $user->id;
                    $data->user_name = $user->name;
                    $data->avatar = $user->avatar;
                    $redis->publish('message', json_encode($data));
                }
            } else {
                $resultBet = false;
            }

            return response()->json([
                'datafields' => $datafields,
                'records' => $matches,
                'teams' => $teams,
                'status' => 'OK',
                'resultBet' => $resultBet,
            ]);
        }

        return view('layouts.matches');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.match.index');
        }

        return view('admin.match.create');
    }

}
