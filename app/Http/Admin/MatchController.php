<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\EventRepository;
use App\Repositories\TeamRepository;
use App\Repositories\LeagueRepository;
use App\Repositories\MatchRepository;
use App\Http\Requests\MatchRequest;
use App\Http\Controllers\Controller;
use Exception;

class MatchController extends Controller
{
    protected $matchRepository;
    protected $teamRepository;
    protected $leagueRepository;
    protected $eventRepository;

    public function __construct(
        MatchRepository $matchRepository,
        TeamRepository $teamRepository,
        LeagueRepository $leagueRepository,
        EventRepository $eventRepository
    ) {
        $this->matchRepository = $matchRepository;
        $this->teamRepository = $teamRepository;
        $this->leagueRepository = $leagueRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
            $matches = [];
            $matches = $this->matchRepository->all();

            return response()->json([
                'datafields' => $datafields,
                'records' => $matches,
                'status' => trans('common.ok'),
            ]);
        }

        return view('admin.match.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataAjax();
        }

        return view('admin.match.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MatchRequest $request)
    {
        $message = trans('message.match.create');
        $check = $this->validate($request, $request->rules);
        try {
            if (!$check->fails()) {
                $data = $request->all();
                $this->matchRepository->create($data);
            } else {
                $message = trans('message.match.error');
                
                return view('admin.match.index')->withError($message);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return view('admin.match.index')->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $home = null;
        $guest = null;
        $match = $this->matchRepository->find($id);
        if (!is_null($match)) {
            $home = $this->teamRepository->find($match->home_id);
            $guest = $this->teamRepository->find($match->guest_id);
        }

        return view('admin.match.show')->with(compact('match', 'home', 'guest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $match = collect([]);
        $home = collect([]);
        $guest = collect([]);
        try {
            if ($request->ajax()) {
                $events = $this->eventRepository->where('id', '=', $id)->all();
                $data = $this->getDataAjax();
                if ($events != null) {
                    $data['datafields_events'] = [
                        'id' => trans('common.id'),
                        'content' => trans('event.content'),
                        'time' => trans('event.time'),
                    ];
                    $data['events'] = $events;
                }

                return response()->json($data);
            }
            
            $match = $this->matchRepository->find($id);
            if (!is_null($match)) {
                $home = $this->teamRepository->find($match->home_id);
                $guest = $this->teamRepository->find($match->guest_id);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return view('admin.match.edit')->with(
            [
                'match' => $match,
                'home' => $home,
                'guest' => $guest,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MatchRequest $request, $id)
    {
        $message = trans('message.match.update');
        $data = $request->all();
        try {
            $this->matchRepository->updateMatch($data, $id);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return back()->with('status', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = trans('match.delete_success');
        try {
            $result = $this->matchRepository->deleteByMatch($id);
            if (!$result) {
                $message = trans('match.delete_errors');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return redirect(route('admin.matches.index'))->with('status', $message);
    }

    public function getDataAjax()
    {
        $datafields = [
            'id' => trans('common.id'),
            'name' => trans('common.name'),
            'logo' => trans('common.logo'),
            'country_id' => trans('common.country'),
            'description' => trans('common.description'),
        ];
        $teams = $this->teamRepository->all();
        $leagues = $this->leagueRepository->all();

        return [
            'datafields' => $datafields,
            'records' => $teams,
            'leagues' => $leagues,
            'status' => trans('common.ok'),
        ];
    }
}
