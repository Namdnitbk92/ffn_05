@extends('layouts.app')

@section('content')
    <div class="page-content">
        @include('layouts.result')
        @if(isset($match))
            {{ Form::open(['method' => 'PUT','route' => ['admin.matches.update', $match->id], 'class' => 'ui segment form', 'name' => 'match-update-form']) }}
            <div class="ui segment">
                <h4 class="ui dividing blue header">{{ trans('match.events_list') }}</h4>
                <div id="events_list"></div>
                <input type="hidden" name="events_data"/>
                @include('layouts.menu', ['id' => 'menu-events'])
            </div>
        @else
            {{ Form::open(['route' => 'admin.matches.store', 'method' => 'post', 'class' => 'ui segment form', 'name' => 'match-create-form']) }}
        @endif
        <div class="ui segment">
            <h4 class="ui dividing blue header">{{ trans('match.team_list') }}</h4>
            <div id="team_list"></div>
        </div>
        <h4 class="ui dividing blue header">{{ trans('match.match_information') }}</h4>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label>{{ trans('match.home_team') }}</label>
                    <div class="two fields">
                        <div class="twelve wide field">
                            <div class="ui ignored info message home-text">
                                @if(isset($home))
                                    {!! Html::image($home->logo, '', ['class' => 'img-size']) !!}
                                    <label>{{ $home->name }}</label>
                                @endif
                            </div>
                            {{ Form::input('hidden', null, null, ['name' => 'home_id']) }}
                        </div>
                        <div class="four wide field">
                            {{ Form::input('number', null, null, ['name' => 'home_goal']) }}
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>{{ trans('match.guest_team') }}</label>
                    <div class="two fields">
                        <div class="four wide field">
                            {{ Form::input('number', null, null, ['name' => 'guest_goal']) }}
                        </div>
                        <div class="twelve wide field">
                            <div class="ui ignored info message guest-text">
                                @if(isset($guest))
                                    {!! Html::image($guest->logo, '', ['class' => 'img-size']) !!}
                                    <label>{{ $guest->name }}</label>
                                @endif
                            </div>
                            {{ Form::input('hidden', null, null, ['name' => 'guest_id']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label>{{ trans('match.leagues') }}</label>
                    <div id="league-list"></div>
                    <input type="hidden" name="league_season_id"
                           value="{{ isset($match) ? $match->league_season_id : '' }}"/>
                </div>
                <div class="field">
                    <label>{{ trans('match.result') }}</label>
                    <input type="text" name="result" value="{{ isset($match) ? $match->result : '' }}">
                </div>
            </div>
        </div>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label>{{ trans('match.rate') }}</label>
                    <div id="rate"></div>
                    <input name="rate" type="hidden" value="{{ isset($match) ? $match->rate : '' }}"/>
                </div>
                <div class="field">
                    <label>{{ trans('match.location') }}</label>
                    <div class="two fields">
                        <div class="twelve wide field">
                            <input type="text" name="address" placeholder="Location"
                                   value="{{ isset($match) ? $match->location : ''}}" disabled>
                            <input type="hidden" name="location">
                        </div>
                        <div class="four wide field">
                            <img onclick="(function(){$('#window').jqxWindow('open');
		      			google.maps.event.trigger(map, 'resize');}())" src="{{ asset('images/map.png') }}" width="40"
                                 height="40"/>
                            @include('layouts.map')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="two fields">
                <div class="field">
                    <label>{{ trans('match.start_time') }}</label>
                    {{ Form::date('start', isset($match) ? $match->start : \Carbon\Carbon::now()) }}
                </div>
                <div class="field">
                    <label>{{ trans('match.end_time') }}</label>
                    {{ Form::date('end', isset($match) ? $match->end : \Carbon\Carbon::now()) }}
                </div>
            </div>
        </div>
        <div class="ui segment">
            <div class="field">
                @if(!isset($match))
                    <button type="submit" class="ui teal button">
                        <i class="check icon"></i> {{ trans('match.create') }}
                    </button>
                @else
                    <button type="submit" class="ui yellow button" name="edit-match">
                        <i class="edit icon"></i> {{ trans('match.update') }}
                    </button>
                @endif
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
