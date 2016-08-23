<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\MatchRepository;

class MatchController extends Controller
{
    protected $matchRepository;

    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
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

            return response()->json([
                'datafields' => $datafields,
                'records' => $matches,
                'status' => trans('common.ok'),
                200,
            ]);
        }

        return view('layouts.matches');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.match.create');
    }

}
