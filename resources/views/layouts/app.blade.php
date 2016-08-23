<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('app.app_name') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/semantic.min.css') }}" rel="stylesheet">
</head>
@if(!Auth::guest())
    <style>
        #app-layout {
            overflow: hidden;
        }
    </style>
@endif
<body id="app-layout">
<div class="ui fixed inverted menu full height">
    <div class="main ui container">
        <a class="launch icon item" onclick="(function () {
                $('.ui.sidebar').sidebar('toggle');
            })()">
            <i class="content icon"></i>
        </a>
        <a href="#" class="header item ct-header">
            {{ trans('app.app_name') }}
        </a>
        @if(!Auth::guest())
            <div class="ui search item">
                <div class="ui icon input">
                    <input class="prompt" type="text" placeholder="Search...">
                    <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
        @endif
        <div class="right menu">
            <a href="#" class="item"><i class="home icon"></i>&nbsp;{{ trans('app.home') }}</a>
            @if(Auth::guest())
                <a class="item" href="{{ url('/login') }}" class="ui inverted button"><i class="sign in icon"></i>&nbsp;
                    {{ trans('login.login') }}
                </a>
            @else
                @can('is_admin', Auth::user())
                    <a href="{{ route('admin.welcome') }}" class="item">
                        <i class="newspaper icon"></i>&nbsp;{{ trans('app.new') }}
                    </a>
                @else
                    <a href="{{ route('users.news.index') }}" class="item">
                        <i class="newspaper icon"></i>&nbsp;{{ trans('app.new') }}
                    </a>
                @endcan
                <div class="ui simple dropdown item">
                    {{ Auth::user()->name }} <i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="{{ url('/logout') }}" class="ui inverted button">
                            <i class="sign out icon"></i>&nbsp; {{ trans('login.logout') }}
                        </a>
                        <a class="item" href="#">
                            <i class="user icon"></i>&nbsp;{{ trans('app.profile') }}
                        </a>
                        <a class="item" href="#">
                            <i class="flag outline icon"></i>&nbsp;
                            {{ trans('app.languages') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="ui left vertical inverted labeled icon sidebar menu uncover">
        <a class="item">
            <i class="flag icon"></i>&nbsp;
            {{ trans('app.league') }}
        </a>
        <a class="item" href="{{ route('admin.teams.index') }}">
        <a class="item">
            <i class="linux icon"></i>&nbsp;
            {{ trans('app.team') }}
        </a>
        @can('is_admin', Auth::user())
            <a class="item" href="{{ route('admin.matches.index') }}">
                <i class="soccer icon"></i>&nbsp;
                {{ trans('app.match') }}
            </a>
        @else
            <a class="item" href="{{ route('users.matches.index') }}">
                <i class="soccer icon"></i>&nbsp;
                {{ trans('app.match') }}
            </a>
            <a class="item" href="{{ route('admin.teams.index') }}">
                <i class="linux icon"></i>&nbsp;
                {{ trans('app.team') }}
            </a>
            <a class="item" href="{{ route('admin.players.index') }}">
                <i class="linux icon"></i>&nbsp;
                {{ trans('app.player') }}
            </a>
        @endcan
        @if(!Auth::guest())
            <div class="ui simple dropdown item">
                {{ Auth::user()->name }} <i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="{{ url('/logout') }}" class="ui inverted button">
                        <i class="sign out icon"></i>&nbsp; {{ trans('login.logout') }}
                    </a>
                    <a class="item" href="#"><i class="user icon"></i>&nbsp;{{ trans('app.profile') }}</a>
                    <a class="item" href="#"><i class="flag outline icon"></i>&nbsp;{{ trans('app.languages') }}</a>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="ui sidebar inverted vertical menu">
    <div class="divider">
        <a class="item">
            <i class="flag icon"></i>&nbsp;{{ trans('app.league') }}
        </a>
        <a class="item">
            <i class="linux icon"></i>&nbsp;{{ trans('app.team') }}
        </a>
        <a class="item">
            <i class="soccer icon"></i>&nbsp;{{ trans('app.match') }}
        </a>
    </div>
</div>
<div class="pusher">
</div>

@yield('content')
@if(!Auth::guest())
    <div class="ui black inverted vertical footer segment">
        <div class="ui center aligned container">
            <div class="ui stackable inverted grid">
                <div class="three wide column">
                    <h4 class="ui inverted header">Company</h4>
                    <div class="ui inverted link list">
                        <a class="item" href="https://github.com/Semantic-Org/Semantic-UI"
                           target="_blank">{{ trans('app.company') }}</a>
                    </div>
                </div>
                <div class="seven wide right floated column">
                    <h4 class="ui inverted teal header">{{ trans('app.app_name') }}</h4>
                    <p> {{ trans('app.describe') }} </p>
                </div>
            </div>
        </div>
    </div>
    <div class="pusher">
    </div>
@if(!Auth::guest())
    <div class="ui stackable grid">
        <div class="equal height row">
            <div class="two wide column">
            </div>
            <div class="twelve wide column">
                <div class="ui segment content">
                    <div class="ui big breadcrumb">
                        <a class="section">{{ trans('app.home') }}</a>
                        <i class="right chevron icon divider"></i>
                        <a class="section">{{ trans('match.match_list') }}</a>
                        <i class="right chevron icon divider"></i>
                        <div class="active section">{{ trans('app.information') }}</div>
                    </div>
                    @yield('content')
                </div>
            </div>
            <div class="two wide column">
            </div>
        </div>
    </div>
@else
    @yield('content')
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/semantic.min.js') }}"></script>
<script src="{{ asset('js/jqx-all.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?json?&mode=transit&origin=frontera+el+hierro&destination=la+restinga+el+hierro&departure_time=1399995076&key=AIzaSyBY2xnVxwjLYhuBNmhiMDUExm-vpUBa-IY&&libraries=places&callback=app.initMap"
        async defer></script>
</body>
</html>
