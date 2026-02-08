@php($user = Auth::user())

<div class="header-content clearfix">
    @if($user != null)
        <div class="nav-control">
            <div class="hamburger">
                <span class="toggle-icon"><i class="icon-menu"></i></span>
            </div>
        </div>
    @endif
    <div class="header-right">
        <ul class="clearfix">
            @if($user != null)
                <li class="icons dropdown">
                    <a href="{{ route('profile') }}">{{ user_full_name($user) }}</a>
                </li>
                <li class="icons dropdown">
                    <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                        <span class="activity active"></span>
                        <img src="{{ asset($user->profile_image) }}" height="40" width="40" alt="">
                    </div>
                    <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                        <div class="dropdown-content-body">
                            <ul>
                                <li>
                                    <a href="{{ route('profile') }}">
                                        <i class="icon-user"></i><span>{{trans('general.profile')}}</span>
                                    </a>
                                </li>
                                <hr class="my-2">
                                <li>
                                    <a href="javascript:void(0)"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-key"></i> <span>{{trans('auth.logout')}}</span>
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </li>
            @else
                <li class="icons dropdown">
                    <a class="text-primary" href="{{ route('login') }}">{{ trans('auth.login') }}</a>
                </li>
                <li class="icons dropdown">
                    <a class="text-primary" href="{{ route('register') }}">{{ trans('auth.register')  }}</a>
                </li>
            @endif
        </ul>
    </div>
</div>