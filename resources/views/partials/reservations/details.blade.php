@section('css')
    <link href="{{asset('/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
@stop

<div class="row">
    <div class="col-md-9">
        @component('components.card')
            @slot('body')
                <div class="row">
                    <div class="col-10">
                        <h3>{{$reservation->property->title}}</h3>
                        <p class="m-0">{{property_location($reservation->property)}}</p>
                    </div>
                </div>
                <p class="text-dark mb-4 mt-3">
                    <strong>{{trans('reservation.confirmation_number')}}:</strong>
                    {{$reservation->confirmation_number}}
                </p>

                <p class="text-dark mb-0 font-weight-bold">
                    <i class="fa fa-users"></i>
                    {{trans('reservation.guests')}}
                </p>
                <p class="pl-3 m-0">{{ $reservation->guest_count }}</p>

                <div class="mt-2 mb-2" style="border-bottom: 1px solid #d4d4d4"></div>
                <p class="text-dark mb-0 font-weight-bold">
                    <i class="fa fa-sign-in"></i>
                    {{trans('reservation.check_in')}}
                </p>
                <p class="pl-3 m-0">{{ date_formatter($reservation->date_start, 'l, M d, Y \a\t 2\P\M') }}</p>

                <div class="mt-2 mb-2" style="border-bottom: 1px solid #d4d4d4"></div>
                <p class="text-dark mb-0 font-weight-bold">
                    <i class="fa fa-sign-out"></i>
                    {{trans('reservation.check_out')}}
                </p>
                <p class="pl-3 mb-0">{{ date_formatter($reservation->date_end, 'l, M d, Y \a\t 12\P\M (\n\o\o\n)') }}</p>

                <div class="mt-2 mb-2" style="border-bottom: 1px solid #d4d4d4"></div>
                <p class="text-dark mb-0 font-weight-bold">
                    <i class="fa fa-location-arrow"></i>
                    {{trans('profile.address')}}
                </p>
                <p class="pl-3 mb-0">{{ property_address($reservation->property) }}</p>

                <div class="mt-2 mb-2" style="border-bottom: 1px solid #d4d4d4"></div>
                <p class="text-dark mb-0 font-weight-bold">
                    <i class="fa fa-tag"></i> {{trans('reservation.cancellation_policy')}}
                </p>
                <p class="pl-3 m-0">{!! cancellation_policy($reservation->property) !!}</p>

                @if($reservation->is_active || $reservation->is_checked_in)
                    <p class="text-dark mb-2 mt-4 font-weight-bold"><i class="fa fa-map-marker" aria-hidden="true"></i>
                        {{trans('reservation.location')}}
                    </p>
                    <div class="d-flex justify-content-center">
                        <iframe frameborder="0" style="border:0;width:100%;min-height:450px"
                                src="{{google_embed_map_url($reservation->property)}}" allowfullscreen></iframe>
                    </div>
                @endif
            @endslot
        @endcomponent
    </div>
    <div class="col-md-3">
        @component('components.card', ['body_class' => 'text-dark'])
            <h5 class="card-title text-center mb-1">
                {{$activity == null ? '' : trans('reservation.activity.'.$user->role_id.'.'.$activity->activity)}}
            </h5>
            <h6 class="text-center m-0">
                {{date_formatter($activity == null ? $reservation->created_at : $activity->created_at, '\o\n l, M d, Y \a\t G:ia')}}
            </h6>
            <div class="d-flex justify-content-center">
                @if(!$user->is_customer && ($reservation->is_active || $reservation->is_checked_in))
                    <div class="btn-group mt-3">
                        <button type="button" class="btn btn-info font-weight-bold">Mark as</button>
                        <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split pl-3 pr-3"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            @if($reservation->is_active)
                                <a class="dropdown-item" href="javascript:void(0)" id="status_btn">
                                    <i class="fa fa-sign-in"></i> {{trans('reservation.check_in')}}
                                </a>
                            @endif
                            @if($reservation->is_checked_in)
                                <a class="dropdown-item" href="javascript:void(0)" id="status_btn">
                                    <i class="fa fa-sign-out"></i> {{trans('reservation.check_out')}}
                                </a>
                            @endif
                            @if($reservation->is_active)
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" id="cancellation_btn">
                                    <i class="fa fa-times"></i> {{trans('general.cancel')}}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endcomponent

        @if(!$user->is_customer)
            @component('components.card', ['body_class' => 'text-dark'])
                <h5 class="card-title text-center">{{trans('reservation.customer')}}</h5>
                <div class="d-flex justify-content-center">
                    <img src="{{asset($reservation->customer->profile_image)}}"
                         style="border-radius:50%;border:2px solid #00A9A2;" width="60" height="60" alt="">
                </div>
                <p class="m-1 text-center font-weight-bold">{{user_full_name($reservation->customer)}}</p>
                <p class="m-0 text-center">
                    <i class="fa fa-phone"></i>
                    <a class="text-primary" href="tel:{{$reservation->customer->phone_number}}">
                        {{format_phone_number($reservation->customer->phone_number)}}
                    </a>
                </p>
                <p class="m-0 text-center">
                    <i class="fa fa-envelope"></i>
                    <a class="text-primary" href="mailto:{{$reservation->customer->email}}">
                        {{$reservation->customer->email}}
                    </a>
                </p>
            @endcomponent
        @endif

        @if(!$user->is_owner)
            @component('components.card', ['body_class' => 'text-dark'])
                <h5 class="text-center card-title">{{trans('property.owner')}}</h5>
                <div class="d-flex justify-content-center">
                    <img src="{{asset($reservation->property->owner->profile_image)}}"
                         style="border-radius:50%;border:2px solid #00A9A2;" width="60" height="60" alt="">
                </div>
                <p class="mb-1 text-center font-weight-bold">{{user_full_name($reservation->property->owner)}}</p>
                @if($user->is_customer)
                    <p class="mt-2 mb-1 text-justify">
                        {{trans('reservation.booking.have_question')}}
                    </p>
                @endif
                <p class="m-0 text-center">
                    <i class="fa fa-phone"></i>
                    <a class="text-primary" href="tel:{{$reservation->property->owner->phone_number}}">
                        {{format_phone_number($reservation->property->owner->phone_number)}}
                    </a>
                </p>
                <p class="m-0 text-center">
                    <i class="fa fa-envelope"></i>
                    <a class="text-primary" href="mailto:{{$reservation->property->owner->email}}">
                        {{$reservation->property->owner->email}}
                    </a>
                </p>
                @if($reservation->is_active && $user->is_customer)
                    <p class="mt-3 text-center">{{trans('reservation.booking.no_longer_want')}}</p>
                    <div class="mt-2 d-flex justify-content-center">
                        <button id="cancellation_btn" class="btn btn-danger">
                            {{trans('reservation.btn.can_cancel')}}
                        </button>
                    </div>
                @endif
            @endcomponent
        @endif

        @if($reservation->is_cancelled)
            @component('components.card', ['body_class' => 'text-dark'])
                <h5 class="card-title text-center">{{trans('reservation.refund.details')}}</h5>
                <div class="mt-2 text-dark">
                    <p class="mt-1 mb-1">
                        {{trans('reservation.total')}}:
                        <span class="pull-right">
                                {{str_replace([':amount'], [number_format($reservation->total_price,2)], trans('reservation.details.price'))}}
                            </span>
                    </p>
                    <p style="border-top: 1px solid #d4d4d4;border-bottom: 1px solid #d4d4d4;" class="mt-1 mb-1">
                        {{trans('reservation.refund.cancellation_fee')}}:
                        <span class="pull-right">
                                {{str_replace([':amount'], [number_format($reservation->cancellation_cut,2)], trans('reservation.details.price'))}}
                            </span>
                    </p>
                    <p class="font-weight-bold mt-2 mb-1">
                        {{trans('reservation.refund.total')}}:
                        <span class="pull-right">
                                {{str_replace([':amount'], [number_format($reservation->refund_cut,2)], trans('reservation.details.price'))}}
                            </span>
                    </p>
                </div>
            @endcomponent
        @endif

        @if(!$reservation->is_cancelled)
            @component('components.card', ['body_class' => 'text-dark'])
                <h5 class="card-title text-center">{{trans('reservation.charges')}}</h5>
                <div class="mt-2">
                    <p class="mb-0 mt-3">
                        <strong>{{trans('reservation.payment_method')}}:</strong>
                        {{trans('reservation.method.'.$reservation->payment->method)}}
                    </p>
                    @if($reservation->payment->method == \SMD\Common\ReservationSystem\Enums\PaymentMethod::CARD)
                        <p class="mb-0">
                            <strong>{{trans('reservation.payment_last4')}}:</strong>
                            •••• {{$reservation->payment->stripe_last4}}
                        </p>
                        <p class="mb-0">
                            <strong>{{trans('reservation.payment_brand')}}:</strong>
                            {{$reservation->payment->stripe_brand}}
                        </p>
                    @endif
                    <div class="d-flex justify-content-between mt-3 mb-1">
                        <div>
                            <p class="m-0">
                                {{str_replace([':price', ':night'], [str_replace([':amount'], [number_format($reservation->price,2)], trans('reservation.details.price')), $reservation->night_count], trans('reservation.details.price_nights'))}}
                            </p>
                        </div>
                        <div>
                            <p class="m-0">
                                {{str_replace([':amount'], [number_format($reservation->price * $reservation->night_count,2)], trans('reservation.details.price'))}}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1 mb-1 pb-1 pt-1"
                         style="border-top: 1px solid #d4d4d4;border-bottom: 1px solid #d4d4d4;">
                        <div><p class="m-0">{{trans('reservation.service_fee')}}</p></div>
                        <div>
                            <p class="m-0">
                                {{str_replace([':amount'], [number_format($reservation->broker_cut,2)], trans('reservation.details.price'))}}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <div><p class="m-0 font-weight-bold">{{trans('reservation.total')}}</p></div>
                        <div>
                            <p class="m-0 font-weight-bold">
                                {{str_replace([':amount'], [number_format($reservation->total_price,2)], trans('reservation.details.price'))}}
                            </p>
                        </div>
                    </div>
                </div>
            @endcomponent
        @endif
    </div>
</div>

@component('components.modal', [
    'modal_id' => 'reservation_cancellation_modal',
    'modal_title' => trans('reservation.cancel_title'),
    'button_class' => 'btn-danger',
    'form' => true,
    'button_text' => trans('general.yes'),
    'after_buttons' => '<button class="btn btn-default" type="button" data-dismiss="modal">' . trans('general.no') . '</button>'
])
    @if($user->is_customer)
        <h5>{{trans('reservation.questions.want_cancel')}}</h5>
        <h6 class="mt-2 mb-2 text-danger font-weight-bold">{!! cancellation_policy($reservation->property) !!}</h6>
    @else
        <h5>{{trans('reservation.questions.why_cancel')}}</h5>
    @endif
    <div class="form-group">
        <textarea id="reservation_cancellation_modal_observation" class="form-control h-150px" rows="6"
                  placeholder="{{trans('reservation.observation')}}"></textarea>
        <div class="text-danger"></div>
    </div>
@endcomponent

@section('scripts')
    <script src="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        function cancellationSuccessful(data) {
            const footer = (data.amount > 0
                ? '<strong>{{trans('reservation.have_been_refund')}}</strong>'
                : '<strong>{{trans('reservation.no_refund')}}</strong>')
                .replace(/:amount/, data.amount);

            Swal.fire({
                icon: 'success',
                title: '{{trans('reservation.cancelled')}}',
                showCloseButton: true,
                showConfirmButton: false,
                text: '{{trans('reservation.successful.canceled')}}',
                footer
            }).then(function () {
                window.location.reload();
            });
        }

        function cancellationError(data) {
            Swal.fire({
                icon: 'error',
                showCloseButton: true,
                showConfirmButton: false,
                title: 'Oops...',
                text: data.error,
                footer: ''
            }).then(function () {
                window.location.reload();
            });
        }

        (function ($) {
            $('#cancellation_btn').on('click', function () {
                $('#reservation_cancellation_modal').modal('show');
            });

            $('#reservation_cancellation_modal_form').on('submit', function (e) {
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    const form = {};

                    const observation = $('#reservation_cancellation_modal_observation');
                    if (observation.val() == '') {
                        $('#reservation_cancellation_modal_observation')
                            .siblings('div.text-danger')
                            .text('{{str_replace([':attribute'], [trans('reservation.observation')], trans('validation.required'))}}');
                        return;
                    }
                    form['observation'] = observation.val();

                    RSApp.jsonPost('{{route('cancel_reservation', ['id' => $reservation->id])}}', form, true, function () {
                        $('#reservation_cancellation_modal_alert_success')
                            .html('<i class="fa fa-spinner spinning"></i> {{trans('reservation.cancelling_reservation')}}')
                            .fadeIn();
                        $('#reservation_cancellation_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            $('#reservation_cancellation_modal').modal('hide');
                            if (data.done) {
                                cancellationSuccessful(data);
                            } else {
                                cancellationError(data);
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                            $('#reservation_cancellation_modal_alert_danger').html(errorThrown).fadeIn();
                        })
                        .always(function () {
                            $('#reservation_cancellation_modal_alert_success').html('').fadeOut();
                            $('#reservation_cancellation_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            @if($reservation->is_checked_in || $reservation->is_active)
            $('#status_btn').on('click', function () {
                Swal.fire({
                    icon: 'question',
                    title: '{{ $reservation->is_active ? trans('reservation.questions.check_in') : ($reservation->is_checked_in ? trans('reservation.questions.check_out') : '') }}',
                    showCancelButton: true,
                    confirmButtonText: '{{trans('general.yes')}}',
                    cancelButtonText: '{{trans('general.no')}}'
                })
                    .then(function (a) {
                        if (a.value) {
                            RSApp.jsonPost('{{route('reservation_status', ['id' => $reservation->id])}}', {
                                status: '{{$reservation->is_active ? \SMD\Common\ReservationSystem\Enums\ReservationStatus::CHECKED_IN : ($reservation->is_checked_in ? \SMD\Common\ReservationSystem\Enums\ReservationStatus::CHECKED_OUT : '')}}'
                            }, true, function () {
                                Swal.fire({
                                    icon: 'info',
                                    iconHtml: '<i class="fa fa-spinner spinning"></i>',
                                    title: '{{trans('general.processing')}}',
                                    showConfirmButton: false
                                });
                            })
                                .done(function (data) {
                                    const options = {
                                        title: data.title,
                                        icon: 'success',
                                        showCloseButton: true,
                                        showConfirmButton: false,
                                        text: data.text
                                    };
                                    if (!data.done) {
                                        options['title'] = 'Oops...';
                                        options['icon'] = 'error';
                                        options['text'] = data.error;
                                    }
                                    Swal.fire(options)
                                        .then(function () {
                                            window.location.reload();
                                        });
                                })
                                .fail(function (a, b, c) {
                                    console.log(b, c);
                                });
                        }
                    });
            });
            @endif
        })(jQuery);
    </script>
@stop