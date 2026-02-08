@extends('partials.master')

@section('body_class', 'h-100')

@section('body')
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    @if(isset($show_actions))
                        <div class="text-right mt-2" style="{{isset($show_actions) ? 'line-height:1.5rem;' : ''}}">
                            <p class="m-0">{{ trans('auth.want_to_post') }}
                                <a class="text-primary mr-2" href="{{url('login')}}">
                                    {{ trans('auth.click_here') }}
                                </a>
                            </p>
                        </div>
                    @endif
                    <div class="form-input-content" style="{{isset($show_actions) ? 'height:calc(100% - 2rem)' : ''}}">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-4">
                                <a class="text-center mb-2" href="{{ url('home') }}">
                                    <h3 class="text-primary">{{ config('app.name') }}</h3>
                                </a>

                                <h5 class="text-center text-gray">@yield('title')</h5>

                                @yield('auth_content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop