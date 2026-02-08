<div class="row">
    <div class="col-md-7 col-lg-9">
        @component('components.card')
            @slot('body')
                <div class="row">
                    <div class="col-10">
                        <h3>{{$property->title}}</h3>
                        <p>{{property_location($property)}}</p>
                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-center ">
                            <img src="{{ asset($property->owner->profile_image) }}"
                                 style="border-radius:50%;border:2px solid #00A9A2;" width="60" height="60" alt="">
                        </div>
                        <p class="text-center mb-0">{{user_full_name($property->owner)}}</p>

                        @if($user != null && $user->is_broker)
                        <p class="text-center">{{$property->owner->phone_number}}</p>
                        @endif
                    </div>
                </div>
                <p class="text-dark mb-0 font-weight-bold"><i
                            class="fa fa-home"></i> {{trans('property.type.'.$property->property_type)}}</p>
                <p class="pl-3 mb-1">{!! property_specs($property) !!}</p>
                <p class="pl-3 text-justify">{{$property->additional_luxury}}</p>
                <p class="text-dark mb-0 font-weight-bold"><i
                            class="fa fa-tag"></i> {{trans('reservation.cancellation_policy')}}
                </p>
                <p class="pl-3">{!! cancellation_policy($property) !!}</p>
                @if(!is_null_or_empty($property->additional_information))
                    <div style="border-bottom: 1px solid #d4d4d4"></div>
                    <p class="text-dark mb-0 mt-3 font-weight-bold"><i class="fa fa-info-circle" aria-hidden="true"></i>
                        {{trans('reservation.additional_info')}}
                    </p>
                    <p class="pl-3 text-justify">{{$property->additional_information}}</p>
                @endif
                <div style="border-bottom: 1px solid #d4d4d4"></div>
                <p class="text-dark mb-2 mt-3 font-weight-bold"><i class="fa fa-map-marker" aria-hidden="true"></i>
                    {{trans('reservation.location')}}
                </p>
                <div class="d-flex justify-content-center">
                    <iframe frameborder="0" style="border:0;width:100%;min-height:450px"
                            src="{{google_embed_map_url($property)}}" allowfullscreen></iframe>
                </div>
            @endslot
        @endcomponent
    </div>
    <div class="col-md-5 col-lg-3">
        @component('components.card')
            @slot('body')
                <h4>
                    $ <span>{{$property->price}}</span> <small>{{trans('reservation.per_night')}}</small>
                </h4>
                <form id="reservation_form">
                    <input id="reservation_check_in" name="check_in" type="hidden"/>
                    <input id="reservation_check_out" name="check_out" type="hidden"/>

                    @if($user != null && ($user->is_broker || $user->is_owner))
                        <div class="form-row">
                            <div class="col pt-1">
                                <label for="reservation_customer_id">{{trans('reservation.customer')}}</label>
                                <select id="reservation_customer_id" class="form-control" role="select2"
                                        data-url="{{route('users_lookup', ['role' => \SMD\Common\ReservationSystem\Enums\RoleType::CUSTOMER])}}"
                                        data-placeholder="{{trans('reservation.customer')}}"
                                        name="customer_id"></select>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    @endif
                    <div class="form-row">
                        <div class="col-12 pt-1">
                            <label>{{trans('reservation.check_in')}}
                                / {{trans('reservation.check_out')}}</label>
                            <input id="reservation_datepicker" class="form-control" type="text"
                                   placeholder="{{trans('reservation.add_dates')}}">
                            <div class="text-danger"></div>
                        </div>
                        <div class="col-12 pt-1">
                            <label for="reservation_guest_count">{{trans('reservation.guests')}}</label>
                            <input type="number" id="reservation_guest_count" min="1" max="{{$property->guest_count}}"
                                   class="form-control" placeholder="{{trans('reservation.add_guests')}}"
                                   name="guest_count">
                            <div class="text-danger"></div>
                            <small>{{str_replace([':guest_count'],[$property->guest_count], trans('reservation.maximum_guest_count'))}}</small>
                        </div>
                    </div>
                    <div id="reservation_pricing" class="mt-4 mb-4 text-dark">
                        <div class="d-flex justify-content-between mt-1 mb-1">
                            <div><p class="m-0" id="reservation_price_detail"></p></div>
                            <div><p class="m-0" id="reservation_sub_total"></p></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1 mb-1 pb-1 pt-1"
                             style="border-top: 1px solid #d4d4d4;border-bottom: 1px solid #d4d4d4;">
                            <div><p class="m-0">{{trans('reservation.service_fee')}}</p></div>
                            <div><p class="m-0" id="reservation_service_fee"></p></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1 mb-1">
                            <div><p class="m-0 font-weight-bold">{{trans('reservation.total')}}</p></div>
                            <div><p class="m-0 font-weight-bold" id="reservation_total_price"></p></div>
                        </div>
                    </div>
                </form>
                <div class="mt-2 d-flex justify-content-center">
                    <div class="col-12">
                    @if($user != null && $user->is_customer)
                        @if(in_array(\SMD\Common\ReservationSystem\Enums\PropertyOption::BY_SYSTEM, $property_options))
                            <div class="row mb-2 justify-content-center">
                                <div class="col-4">
                                    <button id="reservation_btn" class="btn btn-success">{{trans('reservation.btn.reserve')}}</button>
                                </div>
                            </div>
                        @endif

                        @if(in_array(\SMD\Common\ReservationSystem\Enums\PropertyOption::CONNECT_OWNER, $property_options))

                                <div class="row mb-2">
                                    <div class="col-6">
                                        <button id="reservation_owner_email_btn" class="btn btn-success">Email Owner</button>
                                    </div>

                                    <div class="col-6">
                                        <button id="reservation_owner_whatsapp_btn" class="btn btn-success">Whatsapp Owner</button>
                                    </div>
                                </div>
                        @endif

                        @if(in_array(\SMD\Common\ReservationSystem\Enums\PropertyOption::CONNECT_BROKER, $property_options))
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <button id="reservation_broker_email_btn" class="btn btn-success">Email Broker</button>
                                    </div>

                                    <div class="col-6">
                                        <button id="reservation_broker_whatsapp_btn" class="btn btn-success">Whatsapp Broker</button>
                                    </div>
                                </div>


                        @endif
                    @endif

                    @if($user != null && ($user->is_broker || $user->is_owner))
                            <div class="row mb-2 justify-content-center">
                                <div class="col-4">
                                    <button id="reservation_btn" class="btn btn-success">{{trans('reservation.btn.reserve')}}</button>
                                </div>
                            </div>
                    @endif
                    </div>
                </div>

                <div class="text-center mt-2" id="reservation_see_details"></div>
            @endslot
        @endcomponent
    </div>
</div>

@if($user != null)
    @component('components.modal', ['modal_id' => 'modal_reservation_payment', 'modal_title'=>trans('general.payment'), 'form' => true, 'button_class' => 'btn-success', 'button_text' => trans('reservation.btn.proceed')])
        <h5 class="mb-4">{{$property->title}}
            <small class="text-muted">{{property_location($property)}}</small>
        </h5>

        <h6 class="font-weight-bold">
            {{trans('reservation.guests')}}: <span id="modal_reservation_payment_guest_count"
                                                   class="pull-right text-muted"></span>
        </h6>
        <h6 class="font-weight-bold">
            {{trans('reservation.check_in')}}: <span id="modal_reservation_payment_check_in"
                                                     class="pull-right text-muted"></span>
        </h6>
        <h6 class="font-weight-bold">
            {{trans('reservation.check_out')}}: <span id="modal_reservation_payment_check_out"
                                                      class="pull-right text-muted"></span>
        </h6>
        @if($user != null && ($user->is_broker || $user->is_owner))
            <h6 class="font-weight-bold mt-3">
                {{trans('reservation.customer')}}: <span id="modal_reservation_payment_customer"
                                                         class="pull-right text-muted"></span>
            </h6>
        @endif

        <h5 class="mt-4">{{trans('reservation.payment_methods')}}</h5>
        <div id="modal_reservation_payment_sources">
            <div class="loader spinner"></div>
        </div>

        <h5 class="mt-4 mb-0">{{trans('reservation.charges')}}</h5>
        <div id="modal_reservation_payment_pricing" class="mt-2 text-dark">
            <div class="d-flex justify-content-between mt-1 mb-1">
                <div><p class="m-0" id="modal_reservation_payment_price_detail"></p></div>
                <div><p class="m-0" id="modal_reservation_payment_sub_total"></p></div>
            </div>
            <div class="d-flex justify-content-between mt-1 mb-1 pb-1 pt-1"
                 style="border-top: 1px solid #d4d4d4;border-bottom: 1px solid #d4d4d4;">
                <div><p class="m-0">{{trans('reservation.service_fee')}}</p></div>
                <div><p class="m-0" id="modal_reservation_payment_service_fee"></p></div>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <div><p class="m-0 font-weight-bold">{{trans('reservation.total')}}</p></div>
                <div><p class="m-0 font-weight-bold" id="modal_reservation_payment_total_price"></p></div>
            </div>
        </div>
    @endcomponent
@endif

@section('room_scripts')
    <script>
        window.reservedDates = [];
        window.customerReservedDates = [];
        @if($user != null)
            window.template = {
            'ul_source': '<ul class="list-group">__list__</ul>',
            'no_source': '<li class="list-group-item text-center">{{trans('reservation.no_cards_found')}}</li>',
            'item_source': '<li class="list-group-item">\n' +
                '    <div class="form-check">\n' +
                '        <input class="form-check-input" id="card_source___index__" type="radio" name="source_card" value="__id__"\n' +
                '             />\n' +
                '        <label class="form-check-label" for="card_source___index__">\n' +
                '            <span class="font-weight-bold mr-3 text-uppercase">__brand__</span>\n' +
                '            <span class="mr-3">•••• __last4__</span>\n' +
                '            <span class="mr-3">__exp_month__ / __exp_year__</span>\n' +
                '        </label>\n' +
                '    </div>\n' +
                '</li>',
            'cash_source': '<li class="list-group-item">\n' +
                '    <div class="form-check">\n' +
                '        <input class="form-check-input" id="card_source_cash" type="radio" name="source_card" value="cash" />\n' +
                '        <label class="form-check-label font-weight-bold" for="card_source_cash"> {{trans('reservation.pay_cash')}}</label>\n' +
                '    </div>\n' +
                '</li>',
        };

        @endif

        function calculatePrice(start, end, section = 'reservation', price = '{{$property->price}}', fee = {{ $user != null ? $user->broker_cut : '0'}}) {
            const price_pattern = '{{trans('reservation.details.price')}}';
            const price_detail_parent = '{{trans('reservation.details.price_nights')}}';

            const nights = end.diff(start, 'days');
            const subtotal = price * nights;
            const total = (fee * 1) + (subtotal * 1);

            if (section != 'reservation') {
                $('#' + section + '_check_in').text(start.format('MM/DD/YYYY'));
                $('#' + section + '_check_out').text(end.format('MM/DD/YYYY'));
                $('#' + section + '_guest_count').text($('#reservation_guest_count').val());
            }

            $('#' + section + '_price_detail').text(price_detail_parent.replace(/:price/g, price_pattern).replace(/:amount/g, price).replace(/:night/g, nights));
            $('#' + section + '_sub_total').text(price_pattern.replace(/:amount/g, subtotal.toFixed(2)));
            $('#' + section + '_service_fee').text(price_pattern.replace(/:amount/g, fee.toFixed(2)));
            $('#' + section + '_total_price').text(price_pattern.replace(/:amount/g, total.toFixed(2)));
        }

        function getBetweenDates(start, end) {
            const dateArray = [];
            let currentDate = moment(start);
            let stopDate = moment(end);
            while (currentDate <= stopDate) {
                dateArray.push(moment(currentDate).format('YYYY-MM-DD'));
                currentDate = moment(currentDate).add(1, 'days');
            }
            return dateArray;
        }

        function propertyAvailability() {
            const dateRangeText = $('#reservation_datepicker').siblings('div.text-danger');
            const array = getBetweenDates($('#reservation_check_in').val(), $('#reservation_check_out').val());

            const reserved = window.reservedDates.filter(function (item) {
                return array.includes(item);
            });

            $('#reservation_see_details').html('');

            if (reserved.length > 0) {
                        @if($user != null)
                const customerId = $('#reservation_customer_id').val() || '{{$user->id}}';

                const noAvailabilities = window.customerReservedDates.filter(function (item) {
                    return (reserved.includes(item['date_start']) || reserved.includes(item['date_end'])) //&& item['customer_id'] == customerId;
                });

                const customerNoAvailabilities = noAvailabilities.filter(function (item) {
                    return item['customer_id'] == customerId;
                });

                if (customerNoAvailabilities.length > 0) {
                    const link = '<a href="{{route('reservation_details', ['id'=> ':id'])}}">{{trans('reservation.see_details')}}</a>'
                        .replace(/:id/g, customerNoAvailabilities[0]['id']);

                    $('#reservation_btn').text('{{trans('reservation.btn.you_reserved')}}');
                    $('#reservation_see_details').html(link);
                } else {{-- if (noAvailabilities.filter(function (item) { return item['customer_id'] == ''; }).length > 0) --}} {
                    $('#reservation_btn').text('{{trans('reservation.btn.no_availability')}}');
                }
                {{-- else { $('#reservation_btn').text('{{trans('reservation.btn.reserved')}}'); } --}}
                @endif

                $('#reservation_btn').addClass('invalid disabled');
                dateRangeText.text('{{trans('reservation.range_invalid')}}');

                return 1;
            }

            dateRangeText.text('');
            $('#reservation_btn').removeClass('invalid disabled').text('{{trans('reservation.btn.reserve')}}');

            return 0;
        }

        function applyDateRange(start, end) {
            $('#reservation_check_in').val(start.format('YYYY-MM-DD')).trigger('change');
            $('#reservation_check_out').val(end.format('YYYY-MM-DD')).trigger('change');
            propertyAvailability();
            calculatePrice(start, end);
        }

        function getReservedDates() {
            RSApp.jsonGet('{{route('reserved_dates', ['id' => $property->id])}}', function () {
                window['customerReservedDates'] = [];
                window['reservedDates'] = [];
            })
                .done(function (data) {
                    if (data.done) {
                        window['customerReservedDates'] = data.data;
                        $.each(window['customerReservedDates'], function (i, item) {
                            const dates = getBetweenDates(item['date_start'], item['date_end'])
                                .filter(function (date) {
                                    return !window.reservedDates.includes(date);
                                });

                            window.reservedDates = window['reservedDates'].concat(dates);
                        });
                    } else {
                        console.log(data.error);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                })
                .always(function () {
                    propertyAvailability();
                })
        }

        @if($user != null)

        function reservationSuccessful(data) {
            const html = '<p>{{ trans('reservation.your_confirmation_number') }}</p>'
                .replace(/:confirmation_number/g, '<strong>' + data['confirmation_number'] + '</strong>');

            const footer = '<a class="text-primary" href="{{route('reservation_details', ['id'=> ':id'])}}">{{trans('reservation.see_details')}}</a>'
                .replace(/:id/g, data['reservation_id']);

            Swal.fire({
                icon: 'success',
                title: '{{trans('reservation.created')}}',
                showCloseButton: true,
                showConfirmButton: false,
                html,
                footer
            })
        }

        function reservationError(data) {
            Swal.fire({
                icon: 'error',
                showCloseButton: true,
                showConfirmButton: false,
                title: 'Oops...',
                text: data.error
            })
        }

        @endif //

        (function ($) {
            getReservedDates();

            @if($user != null)
            $('#modal_reservation_payment_form').on('submit', function (e) {
                $('#modal_reservation_payment_alert_danger').fadeOut();
                $('#modal_reservation_payment_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    const sourceCard = $('#modal_reservation_payment_form input[name="source_card"]:checked').val();

                    if (sourceCard != undefined && sourceCard != '') {
                        const reservation = {'source_card': sourceCard};

                        $.each($('#reservation_form').serializeArray(), function (i, item) {
                            reservation[item.name] = item.value;
                        });

                        RSApp.jsonPost('{{route('reserve_room', ['id' => $property->id])}}', reservation, true, function () {
                            $('#modal_reservation_payment_form :input').attr('disabled', 'disabled');
                            $('#modal_reservation_payment_alert_success')
                                .html('<i class="fa fa-spinner spinning"></i> {{trans('reservation.processing_payment')}}')
                                .fadeIn();
                        })
                            .done(function (data) {
                                $('#modal_reservation_payment').modal('hide');
                                if (data.done) {
                                    reservationSuccessful(data);
                                } else {
                                    reservationError(data);
                                }
                            })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                                $('#modal_reservation_payment_alert_danger').html(errorThrown).fadeIn();
                            })
                            .always(function () {
                                $('#modal_reservation_payment_alert_success').html('').fadeOut();
                                $('#modal_reservation_payment_form :input').removeAttr('disabled');
                                getReservedDates();
                            });
                    } else {
                        $('#modal_reservation_payment_alert_danger').html('{{trans('reservation.no_card_selected')}}').fadeIn();
                    }
                }
            });

            $('#reservation_customer_id').on('change', function (e) {
                propertyAvailability();
            }).trigger('change');

            $('#modal_reservation_payment').on('show.bs.modal', function (e) {
                const startDate = moment($('#reservation_check_in').val());
                const endDate = moment($('#reservation_check_out').val());

                calculatePrice(startDate, endDate, 'modal_reservation_payment');

                const sourcesElement = $('#modal_reservation_payment_sources');

                let url = '{{route('sources', ['source' => \SMD\Common\Stripe\Enums\SourceType::CARD])}}';

                const customer = $('#reservation_customer_id').val();

                if (customer != undefined && customer != '') {
                    url += '/' + customer;
                    $('#modal_reservation_payment_customer').text($('#reservation_customer_id option:selected').text());
                }

                RSApp.jsonGet(url, function () {
                    $('#modal_reservation_payment_form :input').attr('disabled', 'disabled');
                    sourcesElement.html('<div class="loader spinner"></div>');
                })
                    .done(function (data) {
                        let itemSources = '';

                        //check if only cash
                        @if(in_array(\SMD\Common\ReservationSystem\Enums\PropertyOption::BY_CREDIT, $property_options))
                        if (data.done) {
                            const sources = data.data;
                            if (sources.length > 0) {
                                $.each(sources, function (i, item) {
                                    itemSources += window.template['item_source']
                                        .replace(/__index__/g, i)
                                        .replace(/__id__/g, item['id'])
                                        .replace(/__brand__/g, item['brand'])
                                        .replace(/__exp_year__/g, item['exp_year'])
                                        .replace(/__exp_month__/g, (("0" + item['exp_month']).slice(-2)))
                                        .replace(/__last4__/g, item['last4']);
                                    //.replace(/__checked__/g, (item['id'] == data['default_source'] ? 'checked' : ''));
                                });
                            } else {
                                itemSources += window.template['no_source'];
                            }
                        } else {
                            $('#modal_reservation_payment_alert_danger').html(data.error).fadeIn();
                            itemSources += window.template['no_source'];
                        }
                        @endif

                        itemSources += window.template['cash_source'];

                        sourcesElement.html(window.template['ul_source'].replace(/__list__/g, itemSources));
                        $('input[value="' + data['default_source'] + '"]').prop("checked", true);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        sourcesElement.html(window.template['ul_source'].replace(/__list__/g, window.template['no_source']));
                        $('#modal_reservation_payment_alert_danger').html(errorThrown).fadeIn();
                    })
                    .always(function () {
                        $('#modal_reservation_payment_form :input').removeAttr('disabled');
                    });
            });

            @endif

            //=========================================================
            //=========================================================
            //=========================================================
            //reserve click
            $('#reservation_btn').on('click', function (e) {
                let errors = 0;

                errors += propertyAvailability();

                        @if($user != null && ($user->is_broker || $user->is_owner))
                const customer = $('#reservation_customer_id');
                const customerText = customer.siblings('div.text-danger');

                if (customer.val() == '' || customer.val() == null || customer.val() == undefined) {
                    const text = '{{trans('validation.required')}}'
                        .replace(/:attribute/g, '{{trans('reservation.customer')}}');
                    customerText.text(text);
                    errors += 1;
                }
                        @endif

                const guest = $('#reservation_guest_count');
                const guestText = guest.siblings('div.text-danger');

                if (guest.val() == '') {
                    const text = '{{trans('validation.required')}}'
                        .replace(/:attribute/g, '{{trans('reservation.guests')}}');
                    guestText.text(text);
                    errors += 1;
                } else if (+guest.val() < +guest.attr('min') || +guest.val() > +guest.attr('max')) {
                    const text = '{{trans('validation.between.numeric')}}'
                        .replace(/:min/g, guest.attr('min'))
                        .replace(/:max/g, guest.attr('max'))
                        .replace(/:attribute/g, '{{trans('reservation.guests')}}');
                    guestText.text(text);
                    errors += 1;
                }

                if (errors == 0) {
                    @if($user != null)
                    $('#modal_reservation_payment').modal('show');
                            @else
                    const target = RSApp.formJsonData($('form#form_search_bar'), false);
                    const source = RSApp.formJsonData($('form#reservation_form'), false);
                    $.each(Object.keys(source), function (i, item) {
                        target[item] = source[item];
                    });
                    target['reserve'] = true;
                    window.location.href = '{{route('room', ['id' => $property->id])}}?' + $.param(target);
                    @endif
                }
            });

            //=========================================================
            //=========================================================
            //=========================================================

            $('#reservation_datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: moment(),
                startDate: startDate,
                endDate: endDate,
                opens: 'left',
                showWeekNumbers: true,
                isInvalidDate: function (a) {
                    return reservedDates.includes(a.format('YYYY-MM-DD'));
                },
                isCustomDate(a) {
                    const start = window.customerReservedDates
                        .filter(function (item) {
                            return item['date_start'] == a.format('YYYY-MM-DD');
                        }).length > 0 ? ' is-started' : '';
                    const end = window.customerReservedDates
                        .filter(function (item) {
                            return item['date_end'] == a.format('YYYY-MM-DD');
                        }).length > 0 ? ' is-ended' : '';

                    return reservedDates.includes(a.format('YYYY-MM-DD')) ? 'not-available' + start + end : "";
                }
            }, function (start, end) {
                $('#reservation_check_in').val(start.format('YYYY-MM-DD')).trigger('change');
                $('#reservation_check_out').val(end.format('YYYY-MM-DD')).trigger('change');
                propertyAvailability();
                calculatePrice(start, end);
            });

            $('#reservation_check_in').val(startDate.format('YYYY-MM-DD')).trigger('change');
            $('#reservation_check_out').val(endDate.format('YYYY-MM-DD')).trigger('change');

            $('#reservation_guest_count').val('{{is_null_or_empty(request()->input('guest_count')) ? 1 : request()->input('guest_count')}}');

            calculatePrice(startDate, endDate);

            @if(request()->input('reserve'))
            $('#reservation_btn').trigger('click');
            @endif
        })(jQuery);
    </script>
@stop