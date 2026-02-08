@extends('partials.auth', [
    'show_actions' => true
])

@section('title', trans('general.search_property'))

@section('css')
    <link href="{{asset('/plugins/bootstrap-daterangepicker2/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@stop

@section('auth_content')
    <form class="mt-4 mb-4" method="get" action="{{ url('search') }}">
        <div class="form-group">
            <select id="search_room_zipcode" class="form-control" required name="zipcode"
                    data-minimum-input="1" role="select2" data-allow-clear="true"
                    data-url="{{route('zipcode_lookup')}}" data-role="title-location"
                    data-placeholder="{{trans('property.enter_zipcode')}}" data-allow-clear="true">
            </select>
        </div>
        <div class="form-group">
            <input id="search_check_in" type="hidden" data-role="title-property" name="check_in"/>
            <input id="search_check_out" type="hidden" data-role="title-property" name="check_out"/>
            <input id="search_datepicker" class="form-control" type="text"
                   placeholder="{{trans('reservation.add_dates')}}">
        </div>
        <div class="form-group">
            <input type="text" id="search_guest_count" name="guest_count" class="form-control"
                   placeholder="{{trans('reservation.add_guests')}}" data-role="title-property">
        </div>
        <button class="btn login-form__btn submit w-100">{{ trans('general.search') }}</button>
        <p class="text-center mb-0 mt-4">
            <a class="text-primary" href="{{route('login')}}">{{ trans('auth.login') }}</a> or
            <a class="text-primary" href="{{route('register')}}">{{ trans('auth.btn_sign_up') }}</a>
        </p>
    </form>
@stop

@section('scripts')
    <script src="{{asset('/plugins/moment/moment.js')}}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        (function () {
            $('#search_datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: moment(),
                startDate: moment(),
                endDate: moment().add(1, 'days'),
                opens: 'left',
                showWeekNumbers: true,
                locale: {!! json_encode(trans('general.date_range.locale')) !!},
                ranges: {
                    '{{ trans('general.date_range.ranges.today') }}': [
                        moment(),
                        moment().add(1, 'days')
                    ],
                    '{{ trans('general.date_range.ranges.tomorrow') }}': [
                        moment().add(1, 'days'),
                        moment().add(2, 'days')
                    ],
                    '{{ trans('general.date_range.ranges.this_weekend') }}': [
                        moment().day(6),
                        moment().day(7)
                    ],
                    '{{ trans('general.date_range.ranges.next_weekend') }}': [
                        moment().day(6).add(1, 'weeks'),
                        moment().day(7).add(1, 'weeks')
                    ]
                }
            }, function (start, end) {
                $('#search_check_in').val(start.format('YYYY-MM-DD')).trigger('change');
                $('#search_check_out').val(end.format('YYYY-MM-DD')).trigger('change');
            });
        })($);
    </script>
@stop