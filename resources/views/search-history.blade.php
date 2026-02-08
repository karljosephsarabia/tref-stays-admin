@extends('partials.layout', [
    'breadcrumb' => [
        'items' => $items
    ]
])

@section('title', $title)

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @if($history->count() > 0)
            @foreach($history as $date => $grouped)
                <h5>{{$date}}</h5>
                <div class="row">
                    @foreach($grouped as $item)
                        @if($item->property != null)
                            <div class="col-md-6 col-lg-3">
                                <div class="card card-hover ">
                                    {{--<div class="card-header">
                                        <p class="m-0">
                                            @if($item->user != null && $user->id != $item->rs_user_id)
                                                <span>{{ user_full_name($item->user) }}</span>
                                            @endif
                                            <span class="pull-right">{{date_formatter($item->updated_at, 'h:ia')}}</span>
                                        </p>
                                    </div>--}}
                                    @if($item->property->images->count() > 1)
                                        <div class="carousel slide" id="carousel_{{$item->property->id}}">
                                            <ol class="carousel-indicators">
                                                @foreach($item->property->images as $key => $value)
                                                    <li data-slide-to="{{$key}}"
                                                        data-target="#carousel_{{$item->property->id}}"
                                                        class="{{$loop->last ? "active":""}}"></li>
                                                @endforeach
                                            </ol>
                                            <div class="carousel-inner">
                                                @foreach($item->property->images as $image)
                                                    <div class="carousel-item {{$loop->last ? "active":""}}">
                                                        <img class="d-block w-100 img-carousel" alt=""
                                                             src="{{$image->thumbnail_url ?: $images->url}}">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <a data-slide="prev" href="#carousel_{{$item->property->id}}"
                                               class="carousel-control-prev">
                                                <span class="carousel-control-prev-icon"></span>
                                                <span class="sr-only">{{trans('pagination.previous_1')}}</span>
                                            </a>
                                            <a data-slide="next" href="#carousel_{{$item->property->id}}"
                                               class="carousel-control-next">
                                                <span class="carousel-control-next-icon"></span>
                                                <span class="sr-only">{{trans('pagination.next_1')}}</span>
                                            </a>
                                        </div>
                                    @elseif($item->property->images->count() == 1)
                                        <img class="img-fluid img-carousel" alt=""
                                             src="{{$item->property->images[0]->thumbnail_url ?: $item->property->images[0]->url}}">
                                    @else
                                        <img class="img-fluid img-carousel"
                                             src="/images/properties/images_not_found.png"
                                             alt="">
                                    @endif
                                    <div class="card-body p-4">
                                        <a class="no-hover" target="_blank"
                                           href="{{route('room', ['id' => $item->property->id])}}">
                                            <p class="card-text mb-1">
                                                <span class="text-muted">{{property_type_location($item->property)}}</span>
                                            </p>
                                            <h5 class="card-title mb-1">{{$item->property->title}}</h5>
                                            <p class="card-text mb-1">{!! property_specs($item->property) !!}</p>
                                            <p class="card-text text-justify">{{$item->property->additional_luxury}}</p>
                                        </a>
                                    </div>
                                    <div class="card-footer ">
                                        <a class="no-hover" target="_blank"
                                           href="{{route('room', ['id' => $item->property->id])}}">
                                            <span class="pull-left">
                                                <i class="fa fa-clock-o" aria-hidden="true"></i> {{date_formatter($item->updated_at, 'h:ia')}}
                                            </span>
                                            <p class="card-text text-right font-weight-bold">
                                                $ <span class="text-dark">{{$item->property->price}}</span>
                                                <small>{{trans('reservation.per_night')}}</small>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="pt-5">
                <h1 class="text-muted text-center mt-5">
                    <i class="fa fa-frown-o"></i> No Records
                </h1>
            </div>
        @endif
    </div>
@stop