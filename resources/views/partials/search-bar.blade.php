@component('components.card')
    @slot('body')
        <form id="form_search_bar" method="get" action="{{route('search')}}">
            <div class="row">
                <div class="col-md-2">
                    <select id="search_room_zipcode" class="form-control" name="zipcode"
                            data-minimum-input="1" role="select2" data-allow-clear="true"
                            data-url="{{route('zipcode_lookup')}}" data-role="title-location"
                            data-placeholder="{{trans('property.enter_zipcode')}}"
                            data-allow-clear="true">
                        @if(request()->has('zipcode'))
                            <option value="{{request()->input('zipcode')}}">{{request()->input('zipcode')}}</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="type" id="search_room_type"
                            class="form-control"
                            data-placeholder="Property type..."
                            data-allow-clear="true"
                            data-role="title-property" role="select2">
                    </select>
                </div>

                <div class="col-md-2" id="type_2_div">
                    <select name="type_2" id="search_room_type_2"
                            class="form-control"
                            data-placeholder="{{trans('general.type')}}"
                            data-allow-clear="true"
                            data-role="title-property" role="select2">
                    </select>
                </div>

                <div class="col-md-1" id="type_3_div">
                    <select name="type_3" id="search_room_type_3"
                            class="form-control"
                            data-placeholder="{{trans('general.type')}}"
                            data-allow-clear="true"
                            data-role="title-property" role="select2">
                    </select>
                </div>

                <div class="col-md-3">
                    <div id="accordion-house" class="accordion">
                        <div class="card p-0" style="box-shadow: none">
                            <div class="card-header">
                                <h5 class="mb-0" data-toggle="collapse" data-target="#collapseHouse"
                                    aria-expanded="true" aria-controls="collapseHouse">
                                    <i class="fa" aria-hidden="true"></i> Other filters
                                    <small id="accordion-property-header-small"
                                           class="text-muted float-right"></small>
                                </h5>
                            </div>
                            <div id="collapseHouse" class="collapse" data-parent="#accordion-house">
                                <div class="card-body p-3 pt-2"
                                     style="border: 1px solid #EEE;border-top: 0;">
                                    <div class="form-row type_0_div">
                                        <div class="col">
                                            <label>{{trans('reservation.check_in')}}
                                                / {{trans('reservation.check_out')}}</label>
                                            <input id="search_datepicker" class="form-control"
                                                   type="text"
                                                   placeholder="{{trans('reservation.add_dates')}}">
                                            <input id="search_check_in" type="hidden"
                                                   data-role="title-property"
                                                   name="check_in"/>
                                            <input id="search_check_out" type="hidden"
                                                   data-role="title-property"
                                                   name="check_out"/>
                                        </div>
                                    </div>

                                    <div class="form-row pt-1 type_0_div">
                                        <div class="col">
                                            <label for="search_guest_count">{{trans('reservation.guests')}}</label>
                                            <input type="text" id="search_guest_count"
                                                   name="guest_count"
                                                   class="form-control"
                                                   placeholder="{{trans('reservation.add_guests')}}"
                                                   data-role="title-property">
                                        </div>
                                        <div class="col">
                                            <label for="search_bed_count">{{trans('reservation.bed_count')}}</label>
                                            <input type="text" id="search_bed_count" name="bed_count"
                                                   class="form-control"
                                                   placeholder="{{trans('reservation.add_bed_count')}}"
                                                   data-role="title-property">
                                        </div>
                                    </div>

                                    <div class="form-row pt-1 type_0_div">
                                        <div class="col">
                                            <label for="search_amenities">{{trans('reservation.amenities')}}</label>
                                            <select id="search_amenities" class="form-control" name="amenities"
                                                    data-minimum-input="1" role="select2" data-allow-clear="true"
                                                    data-url="{{route('amenity_lookup')}}" data-role="title-property"
                                                    data-placeholder="{{trans('reservation.amenities')}}"
                                                    data-allow-clear="true" multiple>
                                                @foreach((array)request()->input('amenities', []) as $item)
                                                    <option value="{{$item}}" selected>{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row pt-1" id="type_4_div">
                                        <div class="form-row pt-1">
                                            <div class="col">
                                                <label>Square Feet</label>
                                            </div>
                                        </div>

                                        <div class="form-row pt-0">
                                            <div class="col">
                                                <input type="text" id="search_square_feet_from"
                                                       name="square_feet_from"
                                                       class="form-control"
                                                       placeholder="min square feet"
                                                       data-role="title-property">
                                            </div>
                                            <div class="col-1">to</div>
                                            <div class="col">

                                                <input type="text" id="search_square_feet_to" name="square_feet_to"
                                                       class="form-control"
                                                       placeholder="max square feet"
                                                       data-role="title-property">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row pt-1" id="type_5_div">
                                        <div class="form-row pt-1">
                                            <div class="col">
                                                <label>Leasing Years</label>
                                            </div>
                                        </div>

                                        <div class="form-row pt-0">
                                            <div class="col">
                                                <input type="text" id="search_lease_year_from"
                                                       name="lease_year_from"
                                                       class="form-control"
                                                       placeholder="min leasing years"
                                                       data-role="title-property">
                                            </div>
                                            <div class="col-1">to</div>
                                            <div class="col">

                                                <input type="text" id="search_lease_year_to" name="lease_year_to"
                                                       class="form-control"
                                                       placeholder="max leasing years"
                                                       data-role="title-property">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row pt-1" id="type_6_div">
                                        <div class="col">
                                            <label for="search_room_type">Date Posted</label>
                                            <select name="date_posted" id="search_room_date_posted"
                                                    class="form-control"
                                                    data-placeholder="{{trans('general.type')}}"
                                                    data-allow-clear="true"
                                                    data-role="title-property">
                                                <option value="all_dates">All Dates</option>
                                                <option value="within_24_hours">Within the last 24 hours</option>
                                                <option value="within_3_days">Within the last 3 days</option>
                                                <option value="within_last_week">Within the last week</option>
                                                <option value="within_last_2_weeks">Within the last 2 weeks</option>
                                                <option value="within_last_month">Within the last month</option>
                                                <option value="within_last_3_months">Within the last 3 months</option>
                                                <option value="within_last_year">Within the last year</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-1 align-items-end mr-0">
                    <input name="view" type="hidden" value="{{$view}}"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-magnifier"></i> <span>{{trans('general.search')}}</span>
                    </button>
                </div>

                <div class="col-md-1  align-items-end ml-0">
                    <button type="button" class="btn btn-outline-dark" onclick="changeView('{{$view}}')">
                        @if($view == 'map')
                            <span>Grid</span>
                            <i class="fa fa-th"></i>
                        @elseif($view == 'grid')
                            <span>Map</span>
                            <i class="fa fa-map"></i>
                        @endif
                    </button>
                </div>
            </div>
        </form>
    @endslot
@endcomponent

@section('search_scripts')
    <script>
        function checkValue(value, text, key) {
            return (value == undefined || value == '') ? value : text.replace(key, value);
        }

        function changeView(view = 'grid') {
            $('input[name="view"]').val(((view == '' ? 'grid' : view) == 'grid') ? 'map' : 'grid');
            const form = $('form#form_search_bar');
            form.data('submit', 'submit');

            window.location.href = '/search?' + $.param(RSApp.formJsonData(form, false));
        }

        function changePropertyType(changeType) {
            var propTypes1 = {
                "rent_short_term": "Short Term Rent",
                "rent_long_term": "Long Term Rent",
                "sale": "Sale"
            };

            var propTypes2 = {
                "rent_short_term": {
                    "room": "House and Apt. Rooms",
                    "hotel": "Full Hotel after the season",
                    "hall": "Simche Hall",
                    "parking": "Parking",
                    "pool": "Swimming Pools"
                },
                "rent_long_term": {
                    "residential": "Residential",
                    "commercial": "Commercial",
                },
                "sale": {
                    "residential": "Residential",
                    "commercial": "Commercial",
                }
            };

            var propTypes3 = {
                "rent_long_term.residential": {
                    "apartment": "Apartment",
                    "house": "House",
                    "parking": "Parking",
                },
                "rent_long_term.commercial": {
                    "office": "Office",
                    "warehouse": "Warehouse"
                },
                "sale.residential": {
                    "apartment": "Apartment",
                    "house": "House",
                },
                "sale.commercial": {
                    "office": "Office",
                    "warehouse": "Warehouse",
                    "hall": "Hall",
                    "parking": "Parking"
                }
            };

            var el1 = $('#search_room_type');
            var el2 = $('#search_room_type_2');
            var el3 = $('#search_room_type_3');

            if (changeType < 1) {
                el1.val('').trigger('change');
                el1.html(' ');
                $.each(propTypes1, function (key, value) {
                    el1.append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
                el1.val('');
            }

            if (changeType < 2) {
                el2.val('').trigger('change');
                el2.html(' ');
            }

            if (changeType < 3) {
                el3.val('').trigger('change');
                el3.html(' ');
            }

            if (changeType < 2 && $('#search_room_type').val() != null) {
                $.each(propTypes2[$('#search_room_type').val()], function (key, value) {
                    el2.append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
                el2.val('');
            }

            if (changeType < 3 && $('#search_room_type').val() !== 'rent_short_term' && $('#search_room_type_2').val() != null) {
                $.each(propTypes3[$('#search_room_type').val() + '.' + $('#search_room_type_2').val()], function (key, value) {
                    el3.append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
                el3.val('');
            }

            //=========================================================
            if($('#search_room_type').val() != null){
                $('#type_2_div').show();

                if ($('#search_room_type').val() !== 'rent_short_term' && $('#search_room_type_2').val() != null) {
                    $('#type_3_div').show();
                } else {
                    $('#type_3_div').hide();
                }
            } else {
                $('#type_2_div').hide();
                $('#type_3_div').hide();
            }

            if($('#search_room_type').val() === 'rent_short_term' || $('#search_room_type').val() == null){
                $('.type_0_div').show();
            } else {
                $('.type_0_div').hide();
            }

            if($('#search_room_type').val() == 'rent_long_term' || $('#search_room_type').val() == 'sale'){
                $('#type_4_div').show();

                if($('#search_room_type').val() == 'rent_long_term') {
                    $('#type_5_div').show();
                    $('#type_6_div').hide();
                } else {
                    $('#type_5_div').hide();
                    $('#type_6_div').show();
                }
            } else {
                $('#type_4_div').hide();
                $('#type_5_div').hide();
                $('#type_6_div').hide();
            }

            /*console.log('changePropertyType', changeType);
            console.log('search_room_type', $('#search_room_type').val());
            console.log('search_room_type_2', $('#search_room_type_2').val());
            console.log('search_room_type_3', $('#search_room_type_3').val());*/
        }

        function getLocalityReserve(section, id) {
            return new Promise(function (resolve) {
                const url = '{{route('reverse_zipcode_lookup', [ 'zipcode' => ':id'])}}'
                    .replace(':id', id);

                RSApp.jsonGet(url, function () {
                })
                    .done(function (a) {
                        if (a.done) {
                            const locality = a.results;

                            const data = {id: id, text: locality['zipcode']};

                            if (locality['city']) {
                                data.text += ' ' + locality['city'];
                            }

                            if (locality['state']) {
                                data.text += ', ' + locality['state'];
                            }

                            if (locality['country']) {
                                data.text += ', ' + locality['country'];
                            }

                            RSApp.addOptionToSelect2('#' + section + '_zipcode', data, true);

                            resolve({done: true});
                        } else {
                            resolve(data);
                        }
                    })
                    .fail(function (a, b, c) {
                        resolve({done: false, error: c});
                    });
            });
        }

        function getAmenitiesReserve(section, amenities) {
            return new Promise(function (resolve) {
                let url = '{{route('reverse_amenity_lookup')}}?' + $.param({
                    amenities
                });

                RSApp.jsonGet(url, function () {
                })
                    .done(function (a) {
                        if (a.done) {
                            RSApp.addOptionToSelect2('#' + section + '_amenities', a.results, true);

                            resolve({done: true});
                        } else {
                            resolve(data);
                        }
                    })
                    .fail(function (a, b, c) {
                        resolve({done: false, error: c});
                    });

            });
        }

        const startDate = {!! request()->has('check_in') ? 'moment("'.request()->input('check_in').'")' : 'moment()' !!};
        const endDate = {!! request()->has('check_out') ? 'moment("'.request()->input('check_out').'")' : 'moment().add(1, "days")' !!};

        $(document).ready(function () {
            $('#search_room_type').select2('open');
        });

        (async function ($) {
            $('#search_room_type').val('');
            changePropertyType(0);

            $('#search_room_type').on('select2:select select2:unselect', function () {
                changePropertyType(1);
                $('#form_search_bar').trigger('submit');
                //$('#search_room_type_2').select2('open');
            });

            $('#search_room_type_2').on('select2:select select2:unselect', function () {
                changePropertyType(2);
                $('#form_search_bar').trigger('submit');
                //$('#search_room_type_3').select2('open');
            });

            $('#search_room_type_3').on('select2:select select2:unselect', function () {
                changePropertyType(3);
                $('#form_search_bar').trigger('submit');
            });

            $('#search_datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: moment(),
                startDate: startDate,
                endDate: endDate,
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
                $('#form_search_bar').trigger('submit');
            });

            $('#search_room_zipcode').on('select2:select select2:unselect', function () {
                $('#form_search_bar').trigger('submit');
            });

            $('#search_room_date_posted').on('change', function () {
                $('#form_search_bar').trigger('submit');
            });

            $('#search_bed_count, #search_guest_count').on('change', function (e) {
                $('#form_search_bar').trigger('submit');
            });

            $('[data-role="title-location"]').on('change', function () {
                const options = [$('#search_room_zipcode option:selected').text()]
                    .filter(function (item) {
                        return item != undefined && item != '';
                    });
                $('#accordion-location-header-small').html(options.join(' &raquo; '))
            });

            $('[data-role="title-property"]').on('change', function () {
                const options = [$('#search_room_type option:selected').data('name'),
                    $('#search_datepicker').val(),
                    checkValue($('#search_bed_count').val(), '{{trans('reservation.details.beds')}}', ':beds'),
                    checkValue($('#search_guest_count').val(), '{{trans('reservation.details.guests')}}', ':guests')]
                    .filter(function (item) {
                        return item != undefined && item != '';
                    });
                $('#accordion-property-header-small').html(options.join(' &raquo; '))
            });

            $('#search_check_in').val(startDate.format('YYYY-MM-DD'));
            $('#search_check_out').val(endDate.format('YYYY-MM-DD'));

            $('#search_guest_count').val('{{request()->input('guest_count')}}');
            $('#search_bed_count').val('{{request()->input('bed_count')}}');
            $('#search_room_type').val('{{request()->input('type')}}').trigger('change');
            $('#search_room_type_2').val('{{request()->input('type2')}}').trigger('change');
            $('#search_room_type_3').val('{{request()->input('type3')}}').trigger('change');

            @if(request()->has('zipcode'))
            console.log(await getLocalityReserve('search_room', $.getParams('zipcode', '')))
            @endif
            @if(request()->has('amenities'))
            console.log(await getAmenitiesReserve('search', $.getParams('amenities', [])))
            @endif
        })(jQuery);

    </script>
@stop