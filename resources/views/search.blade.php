@extends('partials.layout', [
    'breadcrumb' => [
        'items' => $items
    ]
])

@section('title', $title)

@section('css')
    <link href="{{asset('/plugins/bootstrap-daterangepicker2/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @include('partials.search-bar')

        @if(isset($property))
            @include('partials.room')
        @else
            @include('partials.rooms.view')
        @endif
    </div>
@stop

@section('scripts')
    <script src="{{asset('/plugins/moment/moment.js')}}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    @yield('search_scripts')

    @yield('rooms_scripts')

    @yield('room_scripts')

@stop