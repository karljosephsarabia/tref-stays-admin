@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.user_logs'), 'route' => route('user_logs'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.user_logs'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <style>
        td.details-control {
            background: url('{{asset('/images/details_open.png')}}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{asset('/images/details_close.png')}}') no-repeat center center;
        }

        .ctable {
            width: 50vw;
            height: 20vh;
            display: flex;
            flex-direction: column;
            background: rgba(148,147,8,0.32)!important;
        }

        .ctable-row {
            width: 100%;
            height: 3vw;
            display: flex;
            border-bottom: 1px solid #ccc;
        }

        .ctable-header {
            font-weight: bold;
        }

        .ctable-row div:first-child {
            width: 10% !important;
        }

        .ctable-row-column {
            width: 30%;
            height: 80%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => 'Users Logs', 'table_id' => 'users_log_datatable'])
            <th></th>
            <th>Date</th>
            <th>IP Address</th>
            <th>Action</th>
        @endcomponent
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        let userlogsTable;
        
        function format ( d ) {
            //console.log(d);
            return '<div class="row">' +
                '<div class="col-12" style="flex: 0 1 100% !important;">' +

                '<div class="row">' +
                '<div class="col-12" style="flex: 0 1 100% !important;">' +
                '<span>Log Id: ' + d.id + '</span>' +
                '</div>' +
                '</div>' +

                '<div class="row">' +
                '<div class="col-12" style="flex: 0 1 100% !important;">' +
                d.log +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
        }
        
        (async function ($) {
            userlogsTable = $('#users_log_datatable').DataTable({
                //order:[[0,'desc']],
                ajax: '{{route('user_logs_datatable')}}',
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        mRender: function (data, type, row) {
                            return moment(row['created_at']).format('MM/DD/YYYY hh:mm:ss a');
                        }
                    },
                    {data: 'user_log_ip'},
                    {data: 'action'}
                ]
            });

            // Add event listener for opening and closing details
            $('#users_log_datatable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = userlogsTable.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );
        })(jQuery);
    </script>
@stop