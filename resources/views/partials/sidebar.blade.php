@php($user = Auth::user())

<div class="nk-nav-scroll">
    <ul class="metismenu" id="menu">
        <li class="nav-label">{{ trans('general.dashboard') }}</li>
        @if($user->email == 'broker2@skymaxservices.com')
            <li>
                <a href="{{ route('hebrew_audio') }}" aria-expanded="false">
                    <i class="icon-control-play menu-icon"></i>
                    <span class="nav-text">Hebrew Audios</span>
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('home') }}" aria-expanded="false"
                   class="{{(Request::is('home*') || Request::is('/*')) ? 'active' : ''}}">
                    <i class="icon-speedometer menu-icon"></i>
                    <span class="nav-text">{{ trans('general.dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('search') }}" aria-expanded="false" class="{{Request::is('search*') ? 'active' : ''}}">
                    <i class="icon-magnifier menu-icon"></i>
                    <span class="nav-text">{{ trans('general.search') }}</span>
                </a>
            </li>
            <li class="nav-label">{{ trans('general.account') }}</li>
            <li>
                <a href="{{ route('profile') }}" aria-expanded="false">
                    <i class="icon-user menu-icon"></i>
                    <span class="nav-text">{{trans('general.profile')}}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reservations') }}" aria-expanded="false"
                   class="{{Request::is('reservations*') ? 'active' : ''}}">
                    <i class="icon-book-open menu-icon"></i>
                    <span class="nav-text">{{ trans('reservation.show_title') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('history') }}" aria-expanded="false"
                   class="{{Request::is('history*') ? 'active' : ''}}">
                    <i class="icon-clock menu-icon"></i>
                    <span class="nav-text">{{ trans('general.search_history') }}</span>
                </a>
            </li>
            @if($user->is_owner || $user->is_broker)
                <li class="nav-label">{{ trans('general.manager') }}</li>
                <li>
                    <a href="{{ route('properties') }}" aria-expanded="false">
                        <i class="icon-home menu-icon"></i>
                        <span class="nav-text">{{trans('general.properties')}}</span>
                    </a>
                </li>
            @endif
            @if($user->is_broker)
                <li>
                    <a href="{{ route('criteria') }}" aria-expanded="false">
                        <i class="icon-magnifier menu-icon"></i>
                        <span class="nav-text">{{trans('general.criteria')}}</span>
                    </a>
                </li>

                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="icon-map menu-icon"></i><span class="nav-text">{{trans('general.location')}}</span>

                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('addresses') }}">{{trans('general.addresses')}}</a></li>
                        <li><a href="{{ route('areas') }}">{{trans('general.areas')}}</a></li>
                        <li><a href="{{ route('point_interests') }}">{{trans('general.point_interests')}}</a></li>
                    </ul>
                </li>

                <!-- CUSTOM MENU -->
                <li>
                    <a href="{{ route('custom-menu') }}" aria-expanded="false">
                        <i class="icon-menu menu-icon"></i>
                        <span class="nav-text">{{trans('general.custom-menu')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users') }}" aria-expanded="false">
                        <i class="icon-people menu-icon"></i>
                        <span class="nav-text">{{trans('general.users')}}</span>
                    </a>
                </li>
            @endif
            @if(false)
                <li>
                    <a href="{{ route('setting') }}" aria-expanded="false">
                        <i class="icon-settings menu-icon"></i>
                        <span class="nav-text">{{trans('general.settings')}}</span>
                    </a>
                </li>
            @endif

            @if($user->is_broker)
                <li>
                    <a href="{{ route('user_logs') }}" aria-expanded="false">
                        <i class="icon-settings menu-icon"></i>
                        <span class="nav-text">{{trans('general.user_logs')}}</span>
                    </a>
                </li>
            @endif

            @if($user->is_broker || $user->id == 1)
                <li>
                    <a href="{{ route('hebrew_audio') }}" aria-expanded="false">
                        <i class="icon-control-play menu-icon"></i>
                        <span class="nav-text">Hebrew Audios</span>
                    </a>
                </li>
            @endif

            @if(!$user->is_customer)
                <li class="nav-label">{{ trans('general.reports') }}</li>
                <li>
                    <a href="{{ route('income_report') }}" aria-expanded="false">
                        <i class="icon-wallet menu-icon"></i>
                        <span class="nav-text">{{trans('general.income')}}</span>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</div>