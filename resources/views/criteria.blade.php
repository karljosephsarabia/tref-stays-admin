@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.criteria'), 'route' => route('criteria'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.criteria'))

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
        @component('components.datatable', ['title' => trans('criteria.title.show_table'), 'table_id' => 'criteria_type_datatable'])
            @slot('buttons')
                <button class="btn btn-primary" data-toggle="modal" data-target="#add_criterion_type_modal">
                    <i class="fa fa-plus" title="{{trans('criteria.add_criterion_type')}}"></i> {{trans('general.add')}}
                </button>
            @endslot

            <th></th>
            <th>#</th>
            <th>{{trans('criteria.columns.type.name')}}</th>
            <th>{{trans('criteria.columns.type.menu_order')}}</th>
            <th>{{ trans('general.actions') }}</th>
        @endcomponent
    </div>

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'add_criterion_type_modal',
                                    'form' => true,
                                    'modal_title' => trans('criteria.title.modal_add_criterion_type'),
                                    'button_text' => trans('general.create')])
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_type_modal_name">{{trans('general.name')}}</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="add_criterion_type_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_type_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="add_criterion_type_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'edit_criterion_type_modal',
                                    'form' => true,
                                    'modal_title' => trans('criteria.title.modal_edit_criterion_type'),
                                    'button_text' => trans('general.save')])
        <input name="id" type="hidden" id="edit_criterion_type_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_criterion_type_modal_name">{{trans('criteria.name')}}</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="edit_criterion_type_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_criterion_type_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="edit_criterion_type_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'add_criterion_modal',
                                    'form' => true,
                                    'modal_title' => trans('criteria.title.modal_add_criterion'),
                                    'button_text' => trans('general.create')])
        <input name="type_id" type="hidden" id="add_criterion_modal_type_id">

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <h4><label>Criteria Group:&nbsp;</label>
                        <label id="add_criterion_modal_group_name">GroupName</label>
                    </h4>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_modal_name">{{trans('general.name')}}</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="add_criterion_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_criterion_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="add_criterion_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="add_criterion_modal_has_quantity">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"
                                   id="add_criterion_modal_has_quantity_input" data-tab="property"
                                   name="has_quantity" value="1"> {{ trans('criteria.has_quantity') }}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="add_criterion_modal_has_distance">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"
                                   id="add_criterion_modal_has_distance_input" data-tab="property"
                                   name="has_distance" value="1"> {{ trans('criteria.has_distance') }}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'edit_criterion_modal',
                                    'form' => true,
                                    'modal_title' => trans('criteria.title.modal_edit_criterion'),
                                    'button_text' => trans('general.save')])
        <input name="id" type="hidden" id="edit_criterion_modal_id">
        <input name="type_id" type="hidden" id="edit_criterion_modal_type_id">

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <h4><label>Criteria Group:&nbsp;</label>
                        <label id="edit_criterion_modal_group_name">GroupName</label>
                    </h4>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_criterion_modal_name">{{trans('criteria.name')}}</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="edit_criterion_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_criterion_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="edit_criterion_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="edit_criterion_modal_has_quantity">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"
                                   id="edit_criterion_modal_has_quantity_input" data-tab="property"
                                   name="has_quantity" value="1"> {{ trans('criteria.has_quantity') }}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="edit_criterion_modal_has_distance">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"
                                   id="edit_criterion_modal_has_distance_input" data-tab="property"
                                   name="has_distance" value="1"> {{ trans('criteria.has_distance') }}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        let criteriaTypeTable;

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            //console.log(d);
            var result = '<div class="row">' +
                '<div class="col-12" style="flex: 0 1 100% !important;">' +
                '<div class="row">' +
                '<div class="col-12" style="flex: 0 1 100% !important;">' +
                '<div class="pull-right">'+
                '<button class="btn btn-primary add-criteria-button" ' +
                'data-type_id="'+d.id+'" data-group_name="'+d.name+'">'+
                '<i class="fa fa-plus" title="{{trans('criteria.title.add_criterion')}}"></i>{{trans('general.add')}}' +
                '</button>'+
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="ctable-row ctable-header">' +
                //'<div class="ctable-row-column ctable-center">#</div>' +
                '<div class="ctable-row-column ctable-center">{{trans('criteria.columns.criteria.name')}}</div>' +
                '<div class="ctable-row-column ctable-center">{{trans('criteria.columns.criteria.menu_order')}}</div>' +
                '<div class="ctable-row-column ctable-center">{{trans('general.actions')}}</div>' +
                '</div>';

            var index;
            for (index = 0; index < d.search_criteria.length; ++index) {
                result += '<div class="ctable-row">' +
                    //'<div class="ctable-row-column ctable-center">'+d.search_criteria[index]['id']+'</div>' +
                    '<div class="ctable-row-column ctable-center">'+d.search_criteria[index]['name']+'</div>' +
                    '<div class="ctable-row-column ctable-center">'+d.search_criteria[index]['menu_order']+'</div>' +

                    '<div class="ctable-row-column ctable-center">'+

                    '<div class="d-flex justify-content-center"><div class="btn-group">' +
                    '<button class="btn btn-success btn-sm edit-criteria-button"' +
                    'data-id="'+d.search_criteria[index]['id']+'"' +
                    ' data-name="'+d.search_criteria[index]['name']+'"' +
                    ' data-menu_order="'+d.search_criteria[index]['menu_order']+'"' +
                    ' data-has_quantity="'+d.search_criteria[index]['has_quantity']+'"' +
                    ' data-has_distance="'+d.search_criteria[index]['has_distance']+'"' +
                    ' data-type_id="'+d.id+'" data-group_name="'+d.name+'">' +
                    '       <i class="fa fa-pencil" title="{{trans('criteria.title.edit_criterion')}}"></i>' +
                    '   </button>' +
                    '   <button class="btn btn-danger btn-sm delete-criteria-button" onclick="deleteCriterion('+ d.search_criteria[index]['id'] +')">' +
                    '       <i class="fa fa-times" title="{{trans('criteria.title.delete_criterion')}}"></i>' +
                    '   </button>' +
                    '</div></div>' +
                    '</div>' +
                    '</div>';
            }

            result += '</div>' +
                '</div>' +
                '</div>';

            return result;
        }

        function deleteCriterionType(criterionTypeId) {
            RSApp.jsonPost("{{route('criterion_type_delete')}}/" + criterionTypeId, this, false, null)
                .done(function (data) {
                    //console.log(data);
                    //===========================
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', 'Criteria Group deleted', 'success', false);
                        criteriaTypeTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                    //===========================
                })
                .fail(function (jqXHR, textStatus, errorThrown){
                    console.log(errorThrown);
                });
        }

        function deleteCriterion(criterionId) {
            RSApp.jsonPost("{{route('criterion_delete')}}/" + criterionId, this, false, null)
                .done(function (data) {
                    //console.log(data);
                    //===========================
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', 'Criterion deleted', 'success', false);
                        criteriaTypeTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                    //===========================
                })
                .fail(function (jqXHR, textStatus, errorThrown){
                    console.log(errorThrown);
                });
        }

        (async function($) {
            let criterion_type_edit = 0;
            let criterion_edit = 0;

            criteriaTypeTable = $('#criteria_type_datatable').DataTable({
               order:[[3,'asc']],
               ajax: '{{route('criteria_datatable')}}',
               columns: [
                   {
                       className: 'details-control',
                       orderable: false,
                       data: null,
                       defaultContent: ''
                   },
                   {data: 'DT_Row_Index'},
                   {data: 'name'},
                   {data: 'menu_order'},

                   {
                       sortable: false,
                       width: 60,
                       mRender: function (data, type, row) {
                           return '<div class="d-flex justify-content-center"><div class="btn-group">' +
                               '   <button class="btn btn-success btn-sm edit-button">' +
                               '       <i class="fa fa-pencil" title="{{trans('address.title.edit_address')}}"></i>' +
                               '   </button>' +
                               '   <button class="btn btn-danger btn-sm delete-button" onclick="deleteCriterionType('+ row['id']+')">' +
                               '       <i class="fa fa-times" title="{{trans('address.title.delete_address')}}"></i>' +
                               '   </button>' +
                               '</div></div>';
                       }
                   }
               ]
            });

            // Add event listener for opening and closing details
            $('#criteria_type_datatable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = criteriaTypeTable.row( tr );

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

            $('#criteria_type_datatable').on('click', '.edit-button', async function () {
                console.log('edit-button');
                var criterionType = criteriaTypeTable.row($(this).parents('tr')).data();
                criterion_type_edit = criterionType.id;
                RSApp.clearForm('edit_criterion_type_modal');
                $('#edit_criterion_type_modal_id').val(criterionType.id);
                $('#edit_criterion_type_modal_name').val(criterionType.name);
                $('#edit_criterion_type_modal_menu_order').val(criterionType.menu_order);


                $('#edit_criterion_type_modal').modal('show');
            });



            $('#add_criterion_type_modal_form').submit(async function(e) {
                $('#add_criterion_type_modal_alert_danger').fadeOut();
                $('#add_criterion_type_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();

                    RSApp.jsonPost("{{route('criterion_type_add')}}", this, false, function() {
                        //$('#add_criterion_type_modal').modal('hide');
                        $('#add_criterion_type_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_criterion_type_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('criteria.type_added') }}', 'success', false);
                                RSApp.clearForm('add_criterion_type_modal');
                                criteriaTypeTable.ajax.reload();
                            } else {
                                $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_criterion_type_modal_');
                            } else {
                                $('#add_criterion_type_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_criterion_type_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#edit_criterion_type_modal_form').submit(async function(e) {
                $('#edit_criterion_type_modal_alert_danger').fadeOut();
                $('#edit_criterion_type_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    RSApp.jsonPost("{{route('criterion_type_edit')}}/" + criterion_type_edit, this, false, function() {
                        $('#edit_criterion_type_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_criterion_type_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('criteria.type_edited') }}', 'success', false);
                                RSApp.clearForm('edit_criterion_type_modal');
                                criteriaTypeTable.ajax.reload();
                            } else {
                                $('#edit_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_criterion_type_modal_');
                            } else {
                                $('#edit_criterion_type_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_criterion_type_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            //======================================================
            $('#criteria_type_datatable').on('click', '.add-criteria-button', async function () {
                //console.log('add-criteria-button', this.dataset);
                RSApp.clearForm('add_criterion_modal');
                $('#add_criterion_modal_type_id').val(this.dataset.type_id);
                $('#add_criterion_modal_group_name').text(this.dataset.group_name);

                $('#add_criterion_modal').modal('show');
            });

            $('#criteria_type_datatable').on('click', '.edit-criteria-button', async function () {
                //console.log('edit-criteria-button', this.dataset);
                RSApp.clearForm('edit_criterion_modal');
                criterion_edit = this.dataset.id;
                $('#edit_criterion_modal_id').val(this.dataset.id);
                $('#edit_criterion_modal_name').val(this.dataset.name);
                $('#edit_criterion_modal_menu_order').val(this.dataset.menu_order);
                $('#edit_criterion_modal_type_id').val(this.dataset.type_id);
                $('#edit_criterion_modal_group_name').text(this.dataset.group_name);

                if (this.dataset.has_quantity === '1') {
                    $('#edit_criterion_modal_has_quantity_input').prop("checked", "checked");
                }
                if (this.dataset.has_distance === '1') {
                    $('#edit_criterion_modal_has_distance_input').prop("checked", "checked");
                }
                $('#edit_criterion_modal').modal('show');
            });

            $('#add_criterion_modal_form').submit(async function(e) {
                $('#add_criterion_modal_alert_danger').fadeOut();
                $('#add_criterion_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    //console.log('form', this);
                    RSApp.jsonPost("{{route('criterion_add')}}", this, false, function() {
                        //$('#add_criterion_modal').modal('hide');
                        $('#add_criterion_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_criterion_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('criteria.type_added') }}', 'success', false);
                                RSApp.clearForm('add_criterion_modal');
                                criteriaTypeTable.ajax.reload();
                            } else {
                                $('#add_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_criterion_modal_');
                            } else {
                                $('#add_criterion_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_criterion_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#edit_criterion_modal_form').submit(async function(e) {
                $('#edit_criterion_modal_alert_danger').fadeOut();
                $('#edit_criterion_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    console.log('editForm', this);
                    RSApp.jsonPost("{{route('criterion_edit')}}/" + criterion_edit, this, false, function() {
                        $('#edit_criterion_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_criterion_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('criteria.type_edited') }}', 'success', false);
                                RSApp.clearForm('edit_criterion_modal');
                                criteriaTypeTable.ajax.reload();
                            } else {
                                $('#edit_property_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_criterion_modal_');
                            } else {
                                $('#edit_criterion_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_criterion_modal_form :input').removeAttr('disabled');
                        });
                }
            });

        })(jQuery);
    </script>


@stop