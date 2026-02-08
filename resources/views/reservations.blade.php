@extends('partials.layout', [
    'breadcrumb' => [
        'items' => $items
    ]
])

@section('title', $title)

@php($user = request()->user())

@section('content_body')
    <div class="container-fluid">
        @if(isset($reservation))
            @include('partials.reservations.details')
        @else
            @include('partials.reservations.view')
        @endif
    </div>
@stop