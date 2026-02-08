@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.custom-menu'), 'route' => route('custom-menu'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.custom-menu'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="card-title">Apt. Finder IVR Menu (Main Menu)</h4>
                            </div>

                            <div class="col-4">
                                <div class="btn-group">
                                    <button class="btn btn-primary play-menu-button" type="button" onclick="playMenuRecording(1,'Main Menu')">
                                        <i class="fa fa-play"></i>
                                    </button>

                                    <button class="btn btn-success edit-menu-button" type="button" onclick="editMenu(1,'Main Menu',0, '{{ $mainMenu ? $mainMenu->tts_text : '' }}')">
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <button class="btn btn-outline-primary add-menu-button" type="button" onclick="addMenu(1)">
                                        Add Menu
                                    </button>

                                    <button class="btn btn-outline-warning add-option-button" type="button" onclick="addOption(1)">
                                        Add Option
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted"><code></code></p>
                        @if(count($menus) > 0)
                            <div id="accordion-1" class="accordion">
                                @foreach($menus as $indexKey => $menu)
                                    @include('partials.recursive-custom-menu', $menu)
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['modal_id' => 'play_modal',
                                    'modal_title' => 'Play',
                                    'button_text' => trans('general.ok'),
                                    'button_close' => true])
        <audio id="audio_player" controls>
            <source id="audio_file" />
        </audio>
        <div>
            <b>{{ trans('general.name') }}: </b>
            <span id="audio_name"></span>
        </div>
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                   'modal_id' => 'add_menu_modal',
                                   'form' => true,
                                   'modal_title' => 'Add Menu',
                                   'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="add_menu_modal_parent_id">
        <input name="id" type="hidden" id="add_menu_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_menu_modal_name">Name</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="add_menu_modal_name"
                           placeholder="Enter menu name">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_menu_modal_menu_order">Dial Number</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="add_menu_modal_menu_order"
                           placeholder="Enter dial number"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_menu_modal_tts_text">Text to Speech Text</label>
                    <input name="tts_text" type="text"
                           class="form-control"
                           id="add_menu_modal_tts_text"
                           placeholder="Enter text to speech text"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        @if(count($unusedMenus) > 0)
            <br>
            <div class="form-row">
                <div class="col">
                    Select from unused existing Menus
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <ul class="list-group">
                        @foreach($unusedMenus as $unusedMenu)
                            <a href="#" class="list-group-item list-group-item-action"
                               onclick="selectMenu({{$unusedMenu->id}},'{{$unusedMenu->name}}', {{$unusedMenu->menu_order}}, '{{$unusedMenu->tts_text}}')">{{$unusedMenu->name}}</a>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'edit_menu_modal',
                                    'form' => true,
                                    'modal_title' => 'Edit Menu',
                                    'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="edit_menu_modal_parent_id">
        <input name="id" type="hidden" id="edit_menu_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_menu_modal_name">Name</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="edit_menu_modal_name"
                           placeholder="Enter menu name"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_menu_modal_menu_order">Dial Number</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="edit_menu_modal_menu_order"
                           placeholder="Enter dial number"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_menu_modal_tts_text">Text to Speech Text</label>
                    <input name="tts_text" type="text"
                           class="form-control"
                           id="edit_menu_modal_tts_text"
                           placeholder="Enter text to speech text"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'del_menu_modal',
                                    'modal_class' => 'modal-danger',
                                    'modal_title' => 'Disable Menu',
                                    'button_class' => 'btn-danger',
                                    'button_text' => 'Disable'])
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                   'modal_id' => 'add_option_modal',
                                   'form' => true,
                                   'modal_title' => 'Add option',
                                   'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="add_option_modal_parent_id">
        <input name="id" type="hidden" id="add_option_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_option_modal_name">Name</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="add_option_modal_name"
                           placeholder="Enter option name">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_option_modal_menu_order">Dial Number</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="add_option_modal_menu_order"
                           placeholder="Enter dial number"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_option_modal_action">Action</label>
                    <select name="action"
                           class="form-control"
                           id="add_option_modal_action">
                        <option disabled selected value> -- Select Option action -- </option>
                        @foreach($optionActions as $optionAction)
                            <option value="{{$optionAction->id}}">{{$optionAction->name}}</option>
                        @endforeach
                    </select>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        @if(count($unusedOptions) > 0)
            <br>
            <div class="form-row">
                <div class="col">
                    Select from unused existing options
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <ul class="list-group">
                        @foreach($unusedOptions as $unusedOption)
                            <a href="#" class="list-group-item list-group-item-action"
                               onclick="selectOption({{$unusedOption->id}},'{{$unusedOption->name}}', {{$unusedOption->menu_order}}, {{$unusedOption->rs_action_id}})">{{$unusedOption->name}}</a>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endcomponent

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'edit_option_modal',
                                    'form' => true,
                                    'modal_title' => 'Edit option',
                                    'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="edit_option_modal_parent_id">
        <input name="id" type="hidden" id="edit_option_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_option_modal_name">Name</label>
                    <input name="name" type="text"
                           class="form-control"
                           id="edit_option_modal_name"
                           placeholder="Enter option name">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_option_modal_menu_order">Dial Number</label>
                    <input name="menu_order" type="text"
                           class="form-control"
                           id="edit_option_modal_menu_order"
                           placeholder="Enter dial number"
                    >
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_option_modal_action">Action</label>
                    <select name="action"
                            class="form-control"
                            id="edit_option_modal_action">
                        <option disabled selected value> -- Select Option action -- </option>
                        @foreach($optionActions as $optionAction)
                            <option value="{{$optionAction->id}}">{{$optionAction->name}}</option>
                        @endforeach
                    </select>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'del_option_modal',
                                    'modal_class' => 'modal-danger',
                                    'modal_title' => 'Disable option',
                                    'button_class' => 'btn-danger',
                                    'button_text' => 'Disable'])
    @endcomponent

@stop

@section('scripts')
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        function playMenuRecording(menuId, menuName) {
            $('#audio_name').html(menuName);
            $('#audio_file').attr('src', '{{ route('recording_play') }}/' + menuId);
            $('#audio_player')[0].pause();
            $('#audio_player')[0].load();
            $('#audio_player')[0].oncanplaythrough = $('#audio_player')[0].play();
            $('#play_modal').modal('show');
        }

        $('#play_modal').on('hidden.bs.modal', function() {
            $('#audio_file').attr('src', '');
            $('#audio_player')[0].pause();
            $('#audio_player')[0].currentTime = 0;
        });

        //==========================================================
        //==========================================================
        //==========================================================
        function addMenu(parentId) {
            RSApp.clearForm('add_menu_modal');
            $('#add_menu_modal_parent_id').val(parentId);
            $('#add_menu_modal').modal('show');
        }

        $('#add_menu_modal_form').submit(async function(e) {
            $('#add_menu_modal_alert_danger').fadeOut();
            $('#add_menu_modal_alert_success').fadeOut();

            if(!e.isDefaultPrevented()){
                e.preventDefault();
                RSApp.jsonPost("{{route('menu_add')}}", this, false, function() {
                    $('#add_menu_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        if (data.done) {
                            $('#add_menu_modal').modal('hide');
                            RSApp.alert('{{ trans('general.done') }}', 'menu successfully saved', 'success', false);
                            RSApp.clearForm('add_menu_modal');
                            location.reload();
                        } else {
                            $('#add_menu_modal_alert_danger').html(data.error).fadeIn();
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        if (jqXHR.status == 422) {
                            RSApp.inputValidation(jqXHR['responseJSON'], 'add_menu_modal_');
                        } else {
                            $('#add_menu_modal_alert_danger').html(jqXHR.responseText).fadeIn();
                        }
                    })
                    .always(function () {
                        $('#add_menu_modal_form :input').removeAttr('disabled');
                    });
            }
        });

        function selectMenu(menuId, menuName, menuOrder, ttsText){
            $('#add_menu_modal_id').val(menuId);
            $('#add_menu_modal_name').val(menuName);
            $('#add_menu_modal_menu_order').val(menuOrder);
            $('#add_menu_modal_tts_text').val(ttsText);
        }

        //==========================================================
        function editMenu(menuId, menuName, menuOrder, ttsText, parentId) {
            RSApp.clearForm('edit_menu_modal');
            $('#edit_menu_modal_name').prop("disabled", false);
            $('#edit_menu_modal_menu_order').prop("disabled", false);

            $('#edit_menu_modal_id').val(menuId);
            $('#edit_menu_modal_name').val(menuName);
            if(menuId === 1) {
                $('#edit_menu_modal_name').prop("disabled", true);
                $('#edit_menu_modal_menu_order').prop("disabled", true);
            } else {
                $('#edit_menu_modal_parent_id').val(parentId);
                $('#edit_menu_modal_menu_order').val(menuOrder);
            }
            $('#edit_menu_modal_tts_text').val(ttsText);
            $('#edit_menu_modal').modal('show');
        }

        $('#edit_menu_modal_form').submit(async function(e) {
            $('#edit_menu_modal_alert_danger').fadeOut();
            $('#edit_menu_modal_alert_success').fadeOut();

            if(!e.isDefaultPrevented()){
                e.preventDefault();
                var edit_menu = $('#edit_menu_modal_id').val();
                RSApp.jsonPost("{{route('menu_edit')}}/" + edit_menu, this, false, function() {
                    $('#edit_menu_modal_form :input').attr('disabled', 'disabled');
                })
                .done(function (data) {
                    if (data.done) {
                        $('#edit_menu_modal').modal('hide');
                        RSApp.alert('{{ trans('general.done') }}', 'Menu successfully edited', 'success', false);
                        RSApp.clearForm('edit_menu_modal');
                        location.reload();
                    } else {
                        $('#edit_menu_modal_alert_danger').html(data.error).fadeIn();
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown){
                    if (jqXHR.status == 422) {
                        RSApp.inputValidation(jqXHR['responseJSON'], 'edit_menu_modal_');
                    } else {
                        $('#edit_menu_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                    }
                })
                .always(function () {
                    $('#edit_menu_modal_form :input').removeAttr('disabled');
                });
            }
        });

        //==========================================================
        function deleteMenu(menuId, menuName) {
            $('#del_menu_modal_button').data('menu-id', menuId);
            let html = '<h4>Please confirm you want to disable menu:' + menuName + '.</h4>';
            html += '<p class="m-0"><strong>MENU ENTRY WILL NOT BE DELETED, JUST DISABLED AND REMOVED FROM MENU.</strong> </p>';
            html += '<p class="m-0"><strong>IT WILL BE AVAILABLE TO BE ADDED AGAIN.</strong> </p>';

            $('#del_menu_modal').find('.modal-body').html(html);
            $('#del_menu_modal').modal('show');
        }

        $('#del_menu_modal_button').click(function () {
            $('#del_menu_modal').modal('hide');
            RSApp.jsonPost("{{ route('menu_delete') }}/" + $(this).data('menu-id')).done(function (data) {
                if (data.done) {
                    RSApp.alert('{{ trans('general.done') }}', 'Menu successfully disabled', 'success', false);
                    location.reload();
                } else {
                    RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
            });
        });

        //==========================================================
        //==========================================================
        //==========================================================
        function addOption(parentId) {
            RSApp.clearForm('add_option_modal');
            $('#add_option_modal_parent_id').val(parentId);
            $('#add_option_modal').modal('show');
        }

        $('#add_option_modal_form').submit(async function(e) {
            $('#add_option_modal_alert_danger').fadeOut();
            $('#add_option_modal_alert_success').fadeOut();

            if(!e.isDefaultPrevented()){
                e.preventDefault();
                RSApp.jsonPost("{{route('option_add')}}", this, false, function() {
                    $('#add_option_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        if (data.done) {
                            $('#add_option_modal').modal('hide');
                            RSApp.alert('{{ trans('general.done') }}', 'option successfully saved', 'success', false);
                            RSApp.clearForm('add_option_modal');
                            location.reload();
                        } else {
                            $('#add_option_modal_alert_danger').html(data.error).fadeIn();
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        if (jqXHR.status == 422) {
                            RSApp.inputValidation(jqXHR['responseJSON'], 'add_option_modal_');
                        } else {
                            $('#add_option_modal_alert_danger').html(jqXHR.responseText).fadeIn();
                        }
                    })
                    .always(function () {
                        $('#add_option_modal_form :input').removeAttr('disabled');
                    });
            }
        });

        function selectOption(optionId, optionName, menuOrder, optionAction){
            $('#add_option_modal_id').val(optionId);
            $('#add_option_modal_name').val(optionName);
            $('#add_option_modal_menu_order').val(menuOrder);
            $('#add_option_modal_action').val(optionAction).change();
        }
        //==========================================================
        function editOption(optionId, optionName, menuOrder, optionAction, parentId) {
            RSApp.clearForm('edit_option_modal');
            $('#edit_option_modal_id').val(optionId);
            $('#edit_option_modal_parent_id').val(parentId);
            $('#edit_option_modal_name').val(optionName);
            $('#edit_option_modal_menu_order').val(menuOrder);
            $('#edit_option_modal_action').val(optionAction).change();
            $('#edit_option_modal').modal('show');
        }

        $('#edit_option_modal_form').submit(async function(e) {
            $('#edit_option_modal_alert_danger').fadeOut();
            $('#edit_option_modal_alert_success').fadeOut();

            if(!e.isDefaultPrevented()){
                e.preventDefault();
                var edit_option = $('#edit_option_modal_id').val();
                RSApp.jsonPost("{{route('option_edit')}}/" + edit_option, this, false, function() {
                    $('#edit_option_modal_form :input').attr('disabled', 'disabled');
                })
                    .done(function (data) {
                        if (data.done) {
                            $('#edit_option_modal').modal('hide');
                            RSApp.alert('{{ trans('general.done') }}', 'option successfully edited', 'success', false);
                            RSApp.clearForm('edit_option_modal');
                            location.reload();
                        } else {
                            $('#edit_option_modal_alert_danger').html(data.error).fadeIn();
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown){
                        if (jqXHR.status == 422) {
                            RSApp.inputValidation(jqXHR['responseJSON'], 'edit_option_modal_');
                        } else {
                            $('#edit_option_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                        }
                    })
                    .always(function () {
                        $('#edit_option_modal_form :input').removeAttr('disabled');
                    });
            }
        });

        //==========================================================
        function deleteOption(optionId, optionName) {
            $('#del_option_modal_button').data('option-id', optionId);
            let html = '<h4>Please confirm you want to disable option:' + optionName + '.</h4>';
            html += '<p class="m-0"><strong>OPTION ENTRY WILL NOT BE DELETED, JUST DISABLED AND REMOVED FROM MENU.</strong> </p>';
            html += '<p class="m-0"><strong>IT WILL BE AVAILABLE TO BE ADDED AGAIN.</strong> </p>';

            $('#del_option_modal').find('.modal-body').html(html);
            $('#del_option_modal').modal('show');
        }

        $('#del_option_modal_button').click(function () {
            $('#del_option_modal').modal('hide');
            RSApp.jsonPost("{{ route('option_delete') }}/" + $(this).data('option-id')).done(function (data) {
                if (data.done) {
                    RSApp.alert('{{ trans('general.done') }}', 'Option successfully disabled', 'success', false);
                    location.reload();
                } else {
                    RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
            });
        });

    </script>
@stop