@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.users'), 'route' => route('users'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.users'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@stop

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => trans('user.show_title'), 'table_id' => 'user_datatable'])
            @slot('buttons')
                <button class="btn btn-primary" id="add_user_modal_button">
                    <i class="fa fa-user-plus" title="{{trans('user.add_user_title')}}"></i> {{trans('general.add')}}
                </button>
            @endslot

            <th>#</th>
            <th>{{ trans('user.first_name') }}</th>
            <th>{{ trans('user.last_name') }}</th>
            <th>{{ trans('user.email') }}</th>
            <th>{{ trans('user.phone_number') }}</th>
            <th>{{ trans('user.role_id') }}</th>
            <th>{{ trans('general.active') }}</th>
            <th>{{ trans('general.actions') }}</th>
        @endcomponent
    </div>

    @component('components.modal', ['modal_id' => 'add_user_modal', 'form' => true, 'modal_title' => trans('user.add_user_title'), 'button_text' => trans('general.create')])
        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#add_user_modal_tab_information">{{trans('general.information')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#add_user_modal_tab_credentials">{{trans('user.credentials')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#add_user_modal_tab_additional">{{trans('general.additional')}}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="add_user_modal_tab_information" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="add_user_modal_first_name">{{ trans('user.first_name') }}</label>
                        <input name="first_name" type="text" class="form-control" id="add_user_modal_first_name"
                               placeholder="{{ trans('user.enter_first_name') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_last_name">{{ trans('user.last_name') }}</label>
                        <input name="last_name" type="text" class="form-control" id="add_user_modal_last_name"
                               placeholder="{{ trans('user.enter_last_name') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_email">{{ trans('user.email') }}</label>
                        <input name="email" type="email" class="form-control" id="add_user_modal_email"
                               placeholder="email@example.com" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_role_id">{{ trans('user.role_id') }}</label>
                        <select id="add_user_modal_role_id" name="role_id" class="form-control" role="select2"
                                data-dropdown-parent="#add_user_modal" data-tab="information"
                                data-target="add-role-owner">
                            @foreach(array_trans($roles, 'user.roles.') as $item => $key)
                                <option value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_phone_number">{{ trans('user.phone_number') }}</label>
                        <input name="phone_number" type="text" class="form-control" id="add_user_modal_phone_number"
                               placeholder="{{ trans('user.enter_phone_number') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group mb-1 form-group-check">
                                <div class="form-check" id="add_user_modal_activated">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input"
                                               id="add_user_modal_activated_input" data-tab="information"
                                               name="activated" value="1" checked> {{ trans('general.active') }}</label>
                                </div>
                                <div class="text-danger"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="add_user_modal_tab_credentials" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="add_user_modal_pin">{{ trans('user.pin') }}</label>
                        <input name="pin" type="text" class="form-control" id="add_user_modal_pin"
                               placeholder="{{ trans('user.enter_pin') }}" data-tab="credentials">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_password">{{ trans('user.password') }}</label>
                        <input name="password" type="password" class="form-control" id="add_user_modal_password"
                               placeholder="{{ trans('user.password') }}" data-tab="credentials">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_password_confirmation">{{ trans('user.password_confirmation') }}</label>
                        <input name="password_confirmation" type="password" class="form-control"
                               id="add_user_modal_password_confirmation" data-tab="credentials"
                               placeholder="{{ trans('user.password_confirmation') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="add_user_modal_tab_additional" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="add_user_modal_commission">{{ trans('user.commission') }}</label>
                        <input name="commission" type="text" class="form-control" id="add_user_modal_commission"
                               placeholder="{{ trans('user.enter_owner_commission') }}" data-tab="additional"
                               data-role="add-role-owner">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="add_user_modal_payment_via">{{ trans('user.payment_via') }}</label>
                        <select id="add_user_modal_payment_via" name="payment_via" class="form-control" role="select2"
                                data-dropdown-parent="#add_user_modal" data-tab="additional" data-role="add-role-owner">
                            @foreach(array_trans($payment_vias, 'user.payment_vias.') as $item => $key)
                                <option value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'edit_user_modal', 'form' => true, 'modal_title' => trans('user.edit_user_title'), 'button_text' => trans('general.save')])
        <div class="default-tab">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab"
                       href="#edit_user_modal_tab_information">{{trans('general.information')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_user_modal_tab_credentials">{{trans('user.credentials')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab"
                       href="#edit_user_modal_tab_additional">{{trans('general.additional')}}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="edit_user_modal_tab_information" role="tabpanel">
                    <input name="id" type="hidden" id="edit_user_modal_user_id">
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_first_name">{{ trans('user.first_name') }}</label>
                        <input name="first_name" type="text" class="form-control" id="edit_user_modal_first_name"
                               placeholder="{{ trans('user.enter_first_name') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_last_name">{{ trans('user.last_name') }}</label>
                        <input name="last_name" type="text" class="form-control" id="edit_user_modal_last_name"
                               placeholder="{{ trans('user.enter_last_name') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_email">{{ trans('user.email') }}</label>
                        <input name="email" type="email" class="form-control" id="edit_user_modal_email" disabled
                               placeholder="email@example.com" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_role_id">{{ trans('user.role_id') }}</label>
                        <select id="edit_user_modal_role_id" name="role_id" class="form-control" role="select2"
                                data-dropdown-parent="#edit_user_modal" data-tab="information"
                                data-target="edit-role-owner">
                            @foreach(array_trans($roles, 'user.roles.') as $item => $key)
                                <option value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_phone_number">{{ trans('user.phone_number') }}</label>
                        <input name="phone_number" type="text" class="form-control" id="edit_user_modal_phone_number"
                               disabled
                               placeholder="{{ trans('user.enter_phone_number') }}" data-tab="information">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1 form-group-check">
                        <div class="form-check" id="edit_user_modal_activated">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="edit_user_modal_activated_input"
                                       name="activated" value="1" data-tab="information"> {{ trans('general.active') }}
                            </label>
                        </div>
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="edit_user_modal_tab_credentials" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_pin">{{ trans('user.pin') }}</label>
                        <input name="pin" type="text" class="form-control" id="edit_user_modal_pin"
                               placeholder="{{ trans('user.enter_pin_edit') }}" data-tab="credentials">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_password">{{ trans('user.password') }}</label>
                        <input name="password" type="password" class="form-control" id="edit_user_modal_password"
                               placeholder="{{ trans('user.enter_password_edit') }}" data-tab="credentials">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_password_confirmation">{{ trans('user.password_confirmation') }}</label>
                        <input name="password_confirmation" type="password" class="form-control"
                               id="edit_user_modal_password_confirmation" data-tab="credentials"
                               placeholder="{{ trans('user.password_confirmation') }}">
                        <div class="text-danger"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="edit_user_modal_tab_additional" role="tabpanel">
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_commission">{{ trans('user.commission') }}</label>
                        <input name="commission" type="text" class="form-control" id="edit_user_modal_commission"
                               placeholder="{{ trans('user.enter_owner_commission') }}" data-tab="additional"
                               data-role="edit-role-owner">
                        <div class="text-danger"></div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="edit_user_modal_payment_via">{{ trans('user.payment_via') }}</label>
                        <select id="edit_user_modal_payment_via" name="payment_via" class="form-control" role="select2"
                                data-dropdown-parent="#edit_user_modal" data-tab="additional"
                                data-role="edit-role-owner">
                            @foreach(array_trans($payment_vias, 'user.payment_vias.') as $item => $key)
                                <option value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'del_user_modal', 'modal_class' => 'modal-danger', 'modal_title' => trans('user.delete_user_title'), 'button_class' => 'btn-danger', 'button_text' => trans('general.delete')])
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
        (function () {
            var user_edit = 0;
            const userTable = $('#user_datatable').DataTable({
                order: [[0, 'desc']],
                ajax: '{{ route('users_datatable') }}',
                "columns": [
                    {data: 'DT_Row_Index'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'role_name'},
                    {data: 'activated_text'},
                    {
                        sortable: false,
                        width: 60,
                        mRender: function (data, type, row) {
                            return '<div class="d-flex justify-content-center"><div class="btn-group">'
                                + '<button class="btn btn-success btn-sm edit-button"><i class="fa fa-pencil" title="{{trans('user.edit_user_title')}}"></i></button>'
                                + (row['id'] !== '{{ Auth::user()->id }}' ? '<button class="btn btn-danger btn-sm delete-button"><i class="fa fa-user-times" title="{{trans('user.delete_user_title')}}"></i></button>' : '')
                                + '</div></div>';
                        }
                    }
                ]
            });

            $('#add_user_modal_form').submit(function (e) {
                $('#add_user_modal_alert_danger').fadeOut();
                $('#add_user_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{ route('user_add') }}", this, false, function () {
                        $('#add_user_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_user_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('user.added') }}', 'success', false);
                                RSApp.clearForm('add_user_modal');
                                userTable.ajax.reload();
                            } else {
                                $('#add_user_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_user_modal_');
                            } else {
                                $('#add_user_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_user_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#edit_user_modal_form').submit(function (e) {
                $('#edit_user_modal_alert_danger').fadeOut();
                $('#edit_user_modal_alert_success').fadeOut();
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{ route('user_edit') }}/" + user_edit, this, false, function () {
                        $('#edit_user_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_user_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{ trans('user.edited') }}', 'success', false);
                                RSApp.clearForm('edit_user_modal');
                                userTable.ajax.reload();
                            } else {
                                $('#edit_user_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_user_modal_');
                            } else {
                                $('#edit_user_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_user_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $('#user_datatable').on('click', '.edit-button', function () {
                var user = userTable.row($(this).parents('tr')).data();
                user_edit = user.id;
                RSApp.clearForm('edit_user_modal');
                $('#edit_user_modal_user_id').val(user.id);
                $('#edit_user_modal_first_name').val(user.first_name);
                $('#edit_user_modal_last_name').val(user.last_name);
                $('#edit_user_modal_email').val(user.email);
                $('#edit_user_modal_phone_number').val(user.phone_number);
                $('#edit_user_modal_pin').val(user.pin);
                $('#edit_user_modal_commission').val(user.commission);
                $('#edit_user_modal_role_id').val(user.role_id).trigger('change');
                $('#edit_user_modal_payment_via').val(user.payment_via).trigger('change');

                if (user.activated) {
                    $('#edit_user_modal_activated_input').prop("checked", "checked").trigger('change');
                }

                $('#edit_user_modal').modal('show');
            });

            $('#add_user_modal_button').on('click', function () {
                $('#add_user_modal_role_id').trigger('change');
                $('#add_user_modal').modal('show');
            });

            $('#user_datatable').on('click', '.delete-button', function () {
                const user = userTable.row($(this).parents('tr')).data();
                $('#del_user_modal_button').data('user-id', user.id);
                let html = '<h4>{{ trans('user.confirm_delete')}}</h4>';
                html += '<p class="m-0"><strong>{{ trans('general.user') }}:</strong> ' + user.first_name + ' ' + user.last_name + '</p>';
                html += '<p class="m-0"><strong>{{ trans('user.email') }}:</strong> ' + user.email + '</p>';
                html += '<p class="m-0"><strong>{{ trans('user.phone_number') }}:</strong> ' + user.phone_number + '</p>';
                $('#del_user_modal').find('.modal-body').html(html);
                $('#del_user_modal').modal('show');
            });

            $('#del_user_modal_button').click(function () {
                $('#del_user_modal').modal('hide');
                RSApp.jsonPost("{{ route('user_delete') }}/" + $(this).data('user-id')).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', '{{ trans('user.deleted') }}', 'success', false);
                        userTable.ajax.reload();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            $('#add_user_modal_role_id, #edit_user_modal_role_id').on('change', function () {
                if ($(this).val() != '{{\SMD\Common\ReservationSystem\Enums\RoleType::OWNER}}') {
                    $('[data-role="' + $(this).data('target') + '"]').attr('disabled', 'disabled');
                } else {
                    $('[data-role="' + $(this).data('target') + '"]').removeAttr('disabled');
                }
            });

            @if(isset($edit_user) && $edit_user->id > 0)
                user_edit = '{{ $edit_user->id }}';
            RSApp.clearForm('edit_user_modal');
            $('#edit_user_modal_user_id').val('{{ $edit_user->id }}');
            $('#edit_user_modal_first_name').val('{{ $edit_user->first_name }}');
            $('#edit_user_modal_last_name').val('{{ $edit_user->last_name }}');
            $('#edit_user_modal_email').val('{{ $edit_user->email }}');
            $('#edit_user_modal_phone_number').val('{{ $edit_user->phone_number }}');
            $('#edit_user_modal_commission').val('{{ $edit_user->commission }}');
            $('#edit_user_modal_role_id').val('{{ $edit_user->role_id }}').trigger('change');
            $('#edit_user_modal_payment_via').val('{{ $edit_user->payment_via }}').trigger('change');

            @if ($edit_user->activated)
            $('#edit_user_modal_activated_input').prop("checked", "checked").trigger('change');
            @endif

            $('#edit_user_modal').modal('show');
            @endif

            $(window).keydown(function (e) {
                if (e.which == 33 || e.which == 34) {
                    e.preventDefault();
                }
            });
        })(jQuery)
    </script>
@stop
