@extends('layouts.app')

@section('content')
    <div class="page-content">
        <h4 class="ui dividing blue header">{{ trans('match.match_information') }}</h4>
        <div class="ui form">
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ trans('match.home_team') }}</label>
                    </div>
                    <div class="field">
                        <label>{{ $home->team }}</label>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>{{ trans('match.guest_team') }}</label>
                    </div>
                    <div class="field">
                        <label>{{ $guest->team }}</label>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ trans('match.result') }}</label>
                    </div>
                    <div class="field">
                        <label>{{ $match->result }}</label>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>{{ trans('match.guest_team') }}</label>
                    </div>
                    <div class="field">
                        <label>{{ $match->rate }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

