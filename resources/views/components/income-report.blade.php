<!-- Income Report -->
<div id="accordion-{{$owner_id ?: 0}}-{{$report->id ?: 0}}" class="accordion">
    <div class="card">
        <div class="card-header" style="cursor:default">
            <a class="mb-0 collapsed text-dark font-weight-bold" data-toggle="collapse" href="javascript:void(0)"
               data-target="#accordion-{{$owner_id ?: 0}}-{{$report->id ?: 0}}-{{$key}}" aria-expanded="false"
               aria-controls="accordion-{{$owner_id ?: 0}}-{{$report->id ?: 0}}-{{$key}}"
               style="font-size:16px;">
                <i class="fa" aria-hidden="true"></i>
                {{$range = report_date_range($report->starting_at, $report->ending_at)}}
            </a>
            @if(!isset($printing))
                <span class="pull-right">
                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                        <a href="{{route('income_pdf', ['id' => $report->id, 'user' => $report->user_id])}}"
                           class="btn btn-info">
                            <i class="fa fa-download"></i> {{trans('report.download_pdf')}}
                        </a>
                        @if(!is_null_or_empty($report->id) && !$report->payment_done)
                            <a href="javascript:void(0)" class="btn btn-success pay_incoming_report"
                               data-id="{{$report->id}}" data-owner="{{user_full_name($report->user)}}"
                               data-range="{{$range}}"
                               data-balance="$ {{number_format($report->ending_balance, 2, '.', '')}}">
                                <i class="fa fa-usd"></i> {{trans('report.pay_invoice')}}
                            </a>
                        @endif
                    </div>
                </span>
            @endif
        </div>
        <div id="accordion-{{$owner_id ?: 0}}-{{$report->id ?: 0}}-{{$key}}" class="collapse"
             data-parent="#accordion-{{$owner_id ?: 0}}-{{$report->id ?: 0}}" style="">
            <div class="card-body text-dark">
                @if(!is_null_or_empty($report->paid_at))
                    <p class="mb-0"><strong>{{trans('report.paid_at')}}:</strong> {{date_formatter($report->paid_at)}}</p>
                    <p class="mb-0">
                        <strong>{{trans('report.paid_via')}}:</strong>
                        {{trans('report.payment_via.'.$report->payment_via)}}
                    </p>
                    @if(!is_null_or_empty($report->comment))
                        <p><strong>{{trans('report.comment')}}:</strong> {{$report->comment}}</p>
                    @endif
                @endif
                <p class="text-right font-weight-bold">
                    {{trans('report.ending_balance')}}: $ {{number_format($report->ending_balance, 2, '.', '')}}
                </p>
                <table class="table">
                    <tr>
                        <th>{{trans('report.date')}}</th>
                        <th>{{trans('report.description')}}</th>
                        <th>{{trans('report.amount')}}</th>
                    </tr>
                    <tr>
                        <td>{{$range}}</td>
                        <td>{{trans('report.total_commissions')}}</td>
                        <td>$ {{number_format($report->commission_total, 2, '.', '')}}</td>
                    </tr>
                    <tr>
                        <td>{{$range}}</td>
                        <td>{{trans('report.total_broker_fees')}}</td>
                        <td>$ {{number_format($report->broker_fee_total, 2, '.', '')}}</td>
                    </tr>
                    <tr>
                        <td>{{$range}}</td>
                        <td>{{trans('report.total_refunds')}}</td>
                        <td>$ {{number_format($report->refund_total, 2, '.', '')}}</td>
                    </tr>
                    <tr>
                        <td>{{$range}}</td>
                        <td>{{trans('report.total_payments')}}</td>
                        <td>$ {{number_format($report->payment_total, 2, '.', '')}}</td>
                    </tr>
                    <tr>
                        <td>{{$range}}</td>
                        <td>{{trans('report.total_reservations')}}</td>
                        <td>$ {{number_format($report->reservation_total, 2, '.', '')}}</td>
                    </tr>
                </table>
                <p class="text-right mb-0 font-weight-bold">
                    {{trans('report.starting_balance')}}: $ {{number_format($report->starting_balance, 2, '.', '')}}
                </p>
            </div>
        </div>
    </div>
</div>