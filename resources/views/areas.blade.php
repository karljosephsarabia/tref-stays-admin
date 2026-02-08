@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.areas'), 'route' => route('areas'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.areas'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="area-accordion">
                    <div class="card-body">
                        @include('partials.area-accordion', ['areas' => $areas])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'add_area_modal',
                                    'form' => true,
                                    'modal_title' => 'Add/Edit Area',
                                    'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="add_area_modal_parent_id">
        <input name="id" type="hidden" id="add_area_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_area_modal_name">{{trans('criteria.name')}}</label>
                    <input name="name" type="text" class="form-control" id="add_area_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_area_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text" class="form-control" id="add_area_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}">
                    <div class="text-danger"></div>
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="add_area_modal_active">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="add_area_modal_active_input"
                                   name="active" value="1"> {{__('general.active')}}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'add_zipcode_modal',
                                    'form' => true,
                                    'modal_title' => 'Add/Edit Zip Code',
                                    'button_text' => trans('general.save')])
        <input name="id" type="hidden" id="add_zipcode_modal_id">
        <input name="area_id" type="hidden" id="add_zipcode_modal_area_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_zipcode_modal_zipcode">Zip Code</label>
                    <input name="zipcode" type="text" class="form-control" id="add_zipcode_modal_zipcode"
                           placeholder="Enter zip code">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'delete_item_modal',
                                    'modal_class' => 'modal-danger',
                                    'modal_title' => 'Delete Item',
                                    'button_class' => 'btn-danger',
                                    'button_text' => trans('general.delete')])
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        function addArea(parent_id, menu_order) {
            $('#add_area_modal_modal_label').text('{{__('areas.add_main_menu')}}');
            addEditArea({parent_id, menu_order, active: true});
        }

        function editArea(json) {
            $('#add_area_modal_modal_label').text('{{__('areas.edit_main_menu')}}');
            addEditArea(JSON.parse(json));
        }

        function addEditArea(item) {
            RSApp.clearForm('add_area_modal');

            $('#add_area_modal_id').val(item.id || '');
            $('#add_area_modal_parent_id').val(item.parent_id);
            $('#add_area_modal_name').val(item.name || '');
            $('#add_area_modal_menu_order').val(item.menu_order);

            if (item.active) {
                $('#add_area_modal_active_input').prop("checked", "checked").trigger('change');
            }

            $('#add_area_modal').modal('show');
        }

        function addZipcode(area_id) {
            $('#add_zipcode_modal_modal_label').text('{{__('areas.add_zipcode')}}');
            addEditZipcode({area_id});
        }

        function editZipcode(json) {
            $('#add_zipcode_modal_modal_label').text('{{__('areas.edit_zipcode')}}');
            addEditZipcode(JSON.parse(json));
        }

        function deleteZipcode(id, zipcode) {
            $('#delete_item_modal_button').data('item-id', id);
            $('#delete_item_modal_button').data('type', 'zipcode');
            let html = '<h4>Please confirm you want to remove zip code: ' + zipcode + '</h4>';

            $('#delete_item_modal').find('.modal-body').html(html);
            $('#delete_item_modal').modal('show');
        }

        function deleteArea(areaId, areaName) {
            $('#delete_item_modal_button').data('item-id', areaId);
            $('#delete_item_modal_button').data('type', 'area');
            let html = '<h4>Please confirm you want to delete area: ' + areaName + '</h4>';
            html += '<p class="m-0"><strong>ALL SUB-AREAS UNDER THIS AREA WILL BE DELETED TOO.</strong> </p>';
            html += '<p class="m-0"><strong>ONCE DELETED IT CANNOT BE RESTORED.</strong> </p>';

            $('#delete_item_modal').find('.modal-body').html(html);
            $('#delete_item_modal').modal('show');
        }

        function addEditZipcode(item) {
            RSApp.clearForm('add_zipcode_modal');
            $('#add_zipcode_modal_area_id').val(item.area_id);
            $('#add_zipcode_modal_id').val(item.id || '');
            $('#add_zipcode_modal_zipcode').val(item.zipcode || '');
            $('#add_zipcode_modal').modal('show');
        }

        function collapseItems() {
            RSApp.jsonGet('{{route('areas_show', ['id' => 0])}}')
                .done(function (data) {
                    const items = $('[id^="row-area-"] [data-toggle="collapse"]');
                    $('#area-accordion .card-body').html(data.render);
                    $.each(items, function (index, item) {
                        if (!$(item).hasClass('collapsed')) {
                            $($(item).data('target')).collapse('toggle');
                        }
                    });
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
        }

        (function () {
            $('#add_area_modal_form').submit(async function (e) {
                $('#add_area_modal_alert_danger').fadeOut();
                $('#add_area_modal_alert_success').fadeOut();

                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{route('area_add_edit')}}", this, false, function () {
                        $('#add_area_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_area_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{__('areas.area_saved')}}', 'success', false);
                                RSApp.clearForm('add_area_modal');
                                collapseItems();
                            } else {
                                $('#add_area_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_area_modal_');
                            } else {
                                $('#add_area_modal_alert_danger').html(jqXHR.responseText).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_area_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#add_zipcode_modal_form').submit(async function (e) {
                $('#add_zipcode_modal_alert_danger').fadeOut();
                $('#add_zipcode_modal_alert_success').fadeOut();

                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{route('area_zipcode_add_edit')}}", this, false, function () {
                        $('#add_zipcode_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_zipcode_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{__('areas.zipcode_saved')}}', 'success', false);
                                RSApp.clearForm('add_zipcode_modal');
                                collapseItems();
                            } else {
                                $('#add_zipcode_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_zipcode_modal_');
                            } else {
                                $('#add_zipcode_modal_alert_danger').html(jqXHR.responseText).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_zipcode_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#delete_item_modal_button').click(function () {
                $('#delete_item_modal').modal('hide');
                const type = $('#delete_item_modal_button').data('type');
                const url = type == 'zipcode'
                    ? "{{ route('area_zipcode_delete') }}/"
                    : type == 'area'
                        ? "{{ route('area_delete') }}/"
                        : '';
                RSApp.jsonPost(url + $('#delete_item_modal_button').data('item-id')).done(function (data) {
                    if (data.done) {
                        const msg = type == 'area'
                            ? '{{__('areas.area_deleted')}}'
                            : type == 'zipcode'
                                ? '{{__('areas.zipcode_deleted')}}'
                                : ''
                        RSApp.alert('{{ trans('general.done') }}', msg, 'success', false);
                        collapseItems();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });
        })();
    </script>
@stop
