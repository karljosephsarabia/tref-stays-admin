@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.reports'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.income'), 'route' => route('income_report'), 'class' => 'active']
        ]
    ]
])

@php($user = Auth::user())

@section('title', trans('general.income'))

@section('content_body')
    <div class="container-fluid">
        @foreach($owners as $owner)
            @component('components.card')
                <h5 class="card-title">{{$user->is_owner ? trans('report.incomes') : user_full_name($owner)}}</h5>
                @component('components.income-report', ['report' => $owner->currentIncomes(), 'key' => 'current', 'owner_id' => $owner->id])
                @endcomponent
                @foreach($owner->incomingReports as $key => $report)
                    @component('components.income-report', ['report' => $report, 'key' => $key, 'owner_id' => $owner->id])
                    @endcomponent
                @endforeach
            @endcomponent
        @endforeach
    </div>

    @component('components.modal', ['modal_id' => 'invoice_payment_modal', 'button_text' => trans('report.pay_invoice'), 'modal_title' => trans('report.pay_invoice_modal'), 'modal_class' => 'text-dark', 'form' => true, 'button_class' => 'btn-success'])
        <p class="mb-0"><strong>Period:</strong> <span id="invoice_payment_modal_range"></span></p>
        <p class="mb-0"><strong>Pay to:</strong> <span id="invoice_payment_modal_owner"></span></p>
        <p><strong>Balance:</strong> <span id="invoice_payment_modal_balance"></span></p>

        <input id="invoice_payment_modal_id" type="hidden" name="id"/>
        <div class="form-group mb-1">
            <label for="invoice_payment_modal_pay_via">{{ trans('report.pay_via') }}</label>
            <select class="form-control" id="invoice_payment_modal_pay_via" name="payment_via">
                @foreach($payment_via as $via)
                    <option value="{{$via}}">{{trans('report.payment_via.'.$via)}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-1">
            <label for="invoice_payment_modal_comment">{{ trans('report.comment') }}</label>
            <textarea class="form-control" id="invoice_payment_modal_comment" name="comment"></textarea>
        </div>
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        (function ($) {
            $('.pay_incoming_report').on('click', function () {
                const report = $(this).data();

                $('#invoice_payment_modal_id').val(report.id);
                $('#invoice_payment_modal_pay_via').val('cash');
                $('#invoice_payment_modal_comment').val('');

                $('#invoice_payment_modal_range').text(report.range);
                $('#invoice_payment_modal_owner').text(report.owner);
                $('#invoice_payment_modal_balance').text(report.balance);

                $('#invoice_payment_modal').modal('show');
            });

            $('#invoice_payment_modal_form').on('submit', function (e) {
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    RSApp.jsonPost('{{route('income_pay')}}', this, false, function () {
                        $('#invoice_payment_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (d) {
                            if (d.done) {
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('report.payment_done') }}', 'success', false);
                                //$('#invoice_payment_modal').modal('hide');
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                $('#invoice_payment_modal_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            $('#invoice_payment_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                        })
                        .always(function () {
                            $('#invoice_payment_modal_form :input').removeAttr('disabled');
                        });
                }
            });
        })(jQuery);
    </script>
@stop