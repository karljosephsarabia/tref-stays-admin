@extends('partials.master')

@php($user = Auth::user())

@section('body')
    <div id="main-wrapper">

        <div class="nav-header" style="background-color: white;">
            <div class="brand-logo" style="background-color: white;">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 50px; width: auto; background-color: white;">
                </a>
            </div>
        </div>

        @if($user != null)
            <div class="nk-sidebar">
                @include('partials.sidebar')
            </div>
        @endif

        <div class="header">
            @include('partials.header')
        </div>

        <div class="content-body" style="{{$user != null ? '' : 'margin-left:0 !important;'}}">
            @if(isset($breadcrumb) && $user != null)
                @component('components.breadcrumb', $breadcrumb)@endcomponent
            @else
                <p class="mt-1"></p>
            @endif

            @yield('content_body')
        </div>

        {{-- Temporary removed
        <div class="footer">
            <div class="copyright">
                <p>{!! str_replace([':link', ':year', ':url', ':text'], ['<a class="text-primary" href=":url">:text</a>', date("Y"), 'http://skymaxcontactcenter.com/', 'Skymax Services'], trans('general.copyright')) !!}</p>
            </div>
        </div>
        --}}
    </div>
@stop
