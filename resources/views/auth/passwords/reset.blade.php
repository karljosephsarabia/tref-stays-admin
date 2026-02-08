@extends('partials.auth')

@section('title', trans('auth.reset_password'))

@section('auth_content')
    <form class="mt-4 login-form" method="post" action="{{ url('password/reset') }}">
        {!! csrf_field() !!}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <input type="email" name="email" class="form-control"
                   placeholder="{{ trans('user.email') }}" value="{{ old('email') }}">
            @if ($errors->has('email'))
                <div id="val-password-error" class="invalid-feedback animated fadeInDown"
                     style="display: block;">{{ $errors->first('email') }}
                </div>
            @endif
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control"
                   placeholder="{{ trans('user.password') }}">
            @if ($errors->has('password'))
                <div id="val-password-error" class="invalid-feedback animated fadeInDown"
                     style="display: block;">{{ $errors->first('password') }}
                </div>
            @endif
        </div>
        <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="{{ trans('user.password_confirmation') }}">
            @if ($errors->has('password_confirmation'))
                <div id="val-password-error" class="invalid-feedback animated fadeInDown"
                     style="display: block;">{{ $errors->first('password_confirmation') }}
                </div>
            @endif
        </div>
        <button class="btn login-form__btn submit w-100">{{ trans('auth.reset_password') }}</button>
        <p class="text-center mt-3 mb-0">
            <a class="text-primary" href="{{route('home')}}">{{trans('general.back')}}</a>
        </p>
    </form>
@stop