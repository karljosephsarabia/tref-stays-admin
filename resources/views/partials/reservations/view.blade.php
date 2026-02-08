@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@stop

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => trans('reservation.show_title'), 'table_id' => 'reservation_datatable'])
            <th>#</th>
            @if(!$user->is_customer)
                <th>{{trans('reservation.customer')}}</th>
            @endif
            <th>{{trans('general.property')}}</th>
            <th>{{trans('general.type')}}</th>
            <th>{{trans('property.owner')}}</th>
            <th>{{trans('reservation.broker')}}</th>
            <th>{{trans('reservation.check_in')}}</th>
            <th>{{trans('reservation.check_out')}}</th>
            <th>{{trans('general.status')}}</th>
            <th>{{trans('general.actions')}}</th>
        @endcomponent
    </div>

    @component('components.modal', ['modal_id' => 'reservation_actions_modal', 'modal_title' => trans('general.actions'), 'button_text' => trans('general.save')])
        <p><strong>Status:</strong> <span id="reservation_actions_modal_status"></span></p>
        <p><strong>Mark as:</strong> <select id="reservation_actions_modal_mark_as"></select></p>
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>

    <script>
        (function ($) {
            const reservationTable = $('#reservation_datatable').DataTable({
                ajax: '{{ route('reservations_datatable') }}',
                order: [[0, 'desc']],
                columns: [
                    {data: 'DT_Row_Index'},
                        @if(!$user->is_customer)
                    {
                        data: 'customer_full_name'
                    },
                        @endif
                    {
                        data: 'property_title'
                    },
                    {data: 'property_type_text'},
                    {data: 'owner_full_name'},
                    {data: 'broker_full_name'},
                    {
                        mRender: function (data, type, row) {
                            return moment(row['date_start']).format('MM/DD/YYYY');
                        }
                    },
                    {
                        mRender: function (data, type, row) {
                            return moment(row['date_end']).format('MM/DD/YYYY');
                        }
                    },
                    {data: 'reservation_status'},
                    {
                        width: 50,
                        mRender: function (data, type, row) {
                            const url = '{{route('reservation_details', ['id' => ':id'])}}'.replace(':id', row['id']);
                            return '<div class="d-flex justify-content-center">'
                                + ' <a class="text-primary" href="' + url + '">{{trans('general.details')}}</a>'
                                + '</div>';
                        }
                    }
                ]
            });
        })(jQuery);
    </script>
@stop
