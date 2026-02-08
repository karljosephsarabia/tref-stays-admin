@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.addresses'), 'route' => route('addresses'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.addresses'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => trans('address.title.show_table'), 'table_id' => 'address_datatable'])
            @slot('buttons')
                <button class="btn btn-primary" id="add_address_modal">
                    <i class="fa fa-plus" title="{{trans('address.title.add_address')}}"></i> {{trans('general.add')}}
                </button>
            @endslot

            <th>#</th>
            <th>{{ trans('profile.phone') }}</th>
            <th>{{ trans('general.name') }}</th>
            <th>{{ trans('address.full_address') }}</th>
            <th>{{ trans('general.actions') }}</th>
        @endcomponent
    </div>

    @component('components.modal', ['modal_id' => 'address_modal', 'modal_title' => '', 'form' => true, 'button_text' => trans('general.save')])
        <input type="hidden" name="id" id="address_modal_address_id"/>
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_phone_number">{{ trans('profile.phone') }}</label>
                    <div class="input-group">
                        <input name="phone_number" type="text" class="form-control"
                               id="address_modal_phone_number" autocomplete="off" value="7184377446"
                               placeholder="{{ trans('address.enter.phone_number') }}">
                        <div class="input-group-append">
                            <div class="input-group-text" id="address_modal_phone_number_search"
                                 style="cursor: pointer">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_name">{{ trans('general.name') }}</label>
                    <input name="name" type="text" class="form-control"
                           id="address_modal_name"
                           placeholder="{{ trans('address.enter.name') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_house_number">{{ trans('address.house_number') }}</label>
                    <input name="house_number" type="text" class="form-control"
                           id="address_modal_house_number"
                           placeholder="{{ trans('address.enter.house_number') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_street_name">{{ trans('property.street_name') }}</label>
                    <input name="street_name" type="text" class="form-control"
                           id="address_modal_street_name"
                           placeholder="{{ trans('address.enter.street_name') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_apt_number">{{ trans('property.apt_number') }}</label>
                    <input name="apt_number" type="text" class="form-control"
                           id="address_modal_apt_number"
                           placeholder="{{ trans('property.enter_apt_number') }}">
                    <div class="text-danger"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_city">{{ trans('property.city') }}</label>
                    <input name="city" type="text" class="form-control"
                           id="address_modal_city"
                           placeholder="{{ trans('address.enter.city') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_state">{{ trans('property.state') }}</label>
                    <input name="state" type="text" class="form-control"
                           id="address_modal_state"
                           placeholder="{{ trans('address.enter.state') }}">
                    <div class="text-danger"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-1">
                    <label for="address_modal_country">{{ trans('property.country') }}</label>
                    <input name="country" type="text" class="form-control"
                           id="address_modal_country"
                           placeholder="{{ trans('address.enter.country') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-6">
                <div class="form-group mb-1">
                    <label for="address_modal_postal_code">{{ trans('address.postal_code') }}</label>
                    <input name="postal_code" type="text" class="form-control"
                           id="address_modal_postal_code"
                           placeholder="{{ trans('address.enter.postal_code') }}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
        <div class="form-row" style="display:none">
            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="address_modal_is_commercial">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input"
                                   id="address_modal_is_commercial_input"
                                   name="is_commercial" value="1"> {{ trans('address.is_commercial') }}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'delete_address_modal', 'modal_title' => trans('address.title.delete_address'), 'button_class' => 'btn-danger', 'button_text' => trans('general.delete')])
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/plugins/tables/js/datatable-init/datatable-basic.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        (async function ($) {
            const addressTable = $('#address_datatable').DataTable({
                order: [[0, 'desc']],
                ajax: '{{ route('addresses_datatable') }}',
                "columns": [
                    {data: 'DT_Row_Index'},
                    {data: 'phone_number'},
                    {data: 'name'},
                    {data: 'full_address'},
                    {
                        sortable: false,
                        width: 60,
                        mRender: function (data, type, row) {
                            return '<div class="d-flex justify-content-center"><div class="btn-group">' +
                                '   <button class="btn btn-success btn-sm edit-button">' +
                                '       <i class="fa fa-pencil" title="{{trans('address.title.edit_address')}}"></i>' +
                                '   </button>' +
                                '   <button class="btn btn-danger btn-sm delete-button">' +
                                '       <i class="fa fa-times" title="{{trans('address.title.delete_address')}}"></i>' +
                                '   </button>' +
                                '</div></div>';
                        }
                    }
                ]
            });

            $('#add_address_modal').on('click', function (e) {
                RSApp.clearForm('address_modal');
                $('#address_modal_modal_label').text('{{trans('address.title.add_address_modal')}}');
                $('#address_modal').modal('show');
            });

            $('#address_datatable').on('click', '.edit-button', async function (e) {
                const address = addressTable.row($(this).parents('tr')).data();
                RSApp.clearForm('address_modal');

                $('#address_modal_phone_number').val(address.phone_number);
                $('#address_modal_address_id').val(address.id);
                $('#address_modal_name').val($('<textarea />').html(address.name).text());
                $('#address_modal_house_number').val(address.house_number);
                $('#address_modal_street_name').val(address.street_name);
                $('#address_modal_apt_number').val(address.apt_number);
                $('#address_modal_country').val(address.country);
                $('#address_modal_state').val(address.state);
                $('#address_modal_city').val(address.city);
                //$('#address_modal_zip4').val(address.zip4);
                $('#address_modal_postal_code').val(address.postal_code);
                if (address.is_commercial == 1) {
                    $('#address_modal_is_commercial_input').prop("checked", "checked").trigger('change');
                }

                $('#address_modal_modal_label').text('{{trans('address.title.edit_address_modal')}}');
                $('#address_modal').modal('show');
            });

            $('#address_datatable').on('click', '.delete-button', function (e) {
                const address = addressTable.row($(this).parents('tr')).data();
                $('#delete_address_modal_button').data('address-id', address.id);

                let html = '<h4>{{ trans('address.confirm_delete')}}</h4>';
                html += '<p class="m-0"><strong>{{ trans('profile.phone') }}:</strong> ' + address.phone_number + '</p>';
                html += '<p class="m-0"><strong>{{ trans('address.full_address') }}:</strong> ' + address.full_address + '</p>';
                $('#delete_address_modal').find('.modal-body').html(html);
                $('#delete_address_modal').modal('show');
            });

            $('#delete_address_modal_button').click(function () {
                $('#delete_address_modal').modal('hide');
                RSApp.jsonPost("{{ route('address_delete') }}/" + $(this).data('address-id')).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', '{{ trans('address.deleted') }}', 'success', false);
                        addressTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            $('#address_modal_phone_number_search').on('click', function (e) {
                $('#address_modal_alert_danger').fadeOut();
                $('#address_modal_alert_success').fadeOut();

                e.preventDefault();
                if (e.isDefaultPrevented) {
                    const url = '{{route('phone_location_lookup', ['number' => ':number'])}}'
                        .replace(/:number/g, $('#address_modal_phone_number').val());

                    RSApp.jsonGet(url, function () {
                        $('#address_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(async function (data) {
                            if (data.done) {
                                $('#address_modal_phone_number').val(data.phone_number);
                                $('#address_modal_address_id').val(data.id);
                                $('#address_modal_name').val(data.name);
                                //$('#address_modal_house_number').val(data.house_number);
                                $('#address_modal_street_name').val(data.street_name);
                                $('#address_modal_city').val(data.city);
                                $('#address_modal_country').val(data.country);
                                $('#address_modal_state').val(data.state);
                                //$('#address_modal_zip4').val(data.zip4);
                                $('#address_modal_postal_code').val(data.postal_code);
                                if (data.is_commercial) {
                                    $('#address_modal_is_commercial_input').prop("checked", "checked").trigger('change');
                                }
                            } else {
                                $('#address_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (a, b, c) {
                            console.log(c);
                        })
                        .always(function () {
                            $('#address_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#address_modal_form').on('submit', function (e) {
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    $('#address_modal_alert_danger').fadeOut();
                    $('#address_modal_alert_success').fadeOut();

                    RSApp.jsonPost("{{ route('address_add_edit') }}", this, false, function () {
                        $('#address_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#address_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('address.updated') }}', 'success', false);
                                RSApp.clearForm('address_modal');
                                addressTable.ajax.reload();
                            } else {
                                $('#address_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'address_modal_');
                            } else {
                                $('#address_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#address_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            @if(isset($edit_address) && $edit_address->id > 0)
            $('#address_modal_phone_number').val('{{$edit_address->phone_number}}');
            $('#address_modal_address_id').val('{{$edit_address->id}}');
            $('#address_modal_name').val('{{$edit_address->name}}');
            $('#address_modal_house_number').val('{{$edit_address->house_number}}');
            $('#address_modal_street_name').val('{{$edit_address->street_name}}');
            $('#address_modal_city').val('{{$edit_address->city}}');
            $('#address_modal_country').val('{{$edit_address->country}}');
            $('#address_modal_state').val('{{$edit_address->state}}');
            //$('#address_modal_zip4').val('{{$edit_address->zip4}}');
            $('#address_modal_postal_code').val('{{$edit_address->postal_code}}');

            @if ($edit_address->is_commercial)
            $('#address_modal_is_commercial_input').prop("checked", "checked").trigger('change');
            @endif

            $('#address_modal_modal_label').text('{{trans('address.title.edit_address_modal')}}');
            $('#address_modal').modal('show');
            @endif
        })(jQuery);
    </script>
@stop