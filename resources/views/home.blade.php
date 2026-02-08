@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.dashboard'), 'route' => route('home'), 'class'=>'active'],
        ]
    ]
])

@php($user = request()->user())

@section('title', trans('general.dashboard'))

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Active Reservations</h5>
                        <div class="active-member">
                            <div class="table-responsive">
                                <table class="table table-xs mb-0 table-striped">
                                    <thead>
                                    <tr>
                                        @if(!$user->is_customer)
                                            <th>{{trans('reservation.customer')}}</th>
                                        @endif
                                        <th>{{trans('general.property')}}</th>
                                        <th>{{trans('general.type')}}</th>
                                        <th>{{trans('property.owner')}}</th>
                                        <th>{{trans('reservation.broker')}}</th>
                                        <th>{{trans('reservation.check_in')}}</th>
                                        <th>{{trans('reservation.check_out')}}</th>
                                        <th>{{trans('general.actions')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            @if(!$user->is_customer)
                                                <td>{{ user_full_name($reservation->customer) }}</td>
                                            @endif
                                            <td>{{ $reservation->property->title }}</td>
                                            <td>{{trans('property.type.'.$reservation->property->property_type)}}</td>
                                            <td>{{ user_full_name($reservation->property->owner) }}</td>
                                            <td>{{ user_full_name($reservation->broker) }}</td>
                                            <td>{{ date_formatter($reservation->date_start) }}</td>
                                            <td>{{ date_formatter($reservation->date_end) }}</td>
                                            <td>
                                                <a class="text-primary"
                                                   href="{{route('reservation_details', ['id'=>$reservation->id])}}">
                                                    {{trans('general.details')}}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(count($reservations) == 0)
                                        <tr>
                                            <td class="text-center" colspan="{{$user->is_customer ? '7' : '8'}}">
                                                {{trans('general.no_records')}}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop