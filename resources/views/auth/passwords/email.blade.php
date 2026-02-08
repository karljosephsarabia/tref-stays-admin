@extends('partials.auth')

@section('title', trans('auth.forgot_password'))

@section('auth_content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form class="mt-4 login-form" method="post" action="{{ url('password/email') }}">
        {!! csrf_field() !!}

        <div class="form-group">
            <input type="email" name="email" class="form-control"
                   placeholder="{{trans('user.email')}}" value="{{ $email ?? old('email') }}">
            @if ($errors->has('email'))
                <div id="val-password-error" class="invalid-feedback animated fadeInDown"
                     style="display: block;">{{ $errors->first('email') }}
                </div>
            @endif
        </div>
        <button class="btn login-form__btn submit w-100">{{ trans('auth.btn_forgot_password') }}</button>
        <p class="text-center mt-3 mb-0">
            <a class="text-primary" href="{{route('home')}}">{{trans('general.back')}}</a>
        </p>
    </form>
@stop