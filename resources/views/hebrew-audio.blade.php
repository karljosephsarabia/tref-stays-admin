@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => 'Hebrew Audios', 'route' => route('hebrew_audio'), 'class' => 'active']
        ]
    ]
])

@section('title', 'Hebrew Audios')

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        @component('components.datatable', ['title' => 'Manage Hebrew Audios', 'table_id' => 'audio_datatable'])
            <th>ID</th>
            <th>Name</th>
            <th>Text to Speech Text</th>
            <th>Edit Text</th>
            <th>English</th>
            <th>Hebrew</th>
        @endcomponent
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
                                    'modal_id' => 'edit_text_modal',
                                    'form' => true,
                                    'modal_title' => 'Edit Text',
                                    'button_text' => trans('general.save')])
        <input name="id" type="hidden" id="edit_text_modal_id">
        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="edit_text_modal_tts_text">TTS Text</label>
                    <input name="tts_text" type="text"
                           class="form-control"
                           id="edit_text_modal_tts_text"
                           placeholder="insert tts text..."
                    >
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
    <script src="{{ asset('/js/rs-app.js') }}"></script>

    <script>
        function playEnglishRecording(menuId, menuName) {
            $('#audio_name').html(menuName);
            $('#audio_file').attr('src', '{{ route('english_recording_play') }}/' + menuId);
            $('#audio_player')[0].pause();
            $('#audio_player')[0].load();
            $('#audio_player')[0].oncanplaythrough = $('#audio_player')[0].play();
            $('#play_modal').modal('show');
        }

        function playHebrewRecording(menuId, menuName) {
            $('#audio_name').html(menuName);
            $('#audio_file').attr('src', '{{ route('hebrew_recording_play') }}/' + menuId);
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

        (function () {
            let audio_edit_id= 0;
            const audioTable = $('#audio_datatable').DataTable({
                order: [[0, 'asc']],
                ajax: '{{ route('hebrew_audio_datatable') }}',
                "columns": [
                    {data: 'DT_Row_Index'},
                    {data: 'name', name: 'name'},
                    {data: 'tts_text', name: 'tts_text'},
                    {
                        sortable: false,
                        width: 60,
                        mRender: function (data, type, row) {
                            return '<div class="d-flex justify-content-center"><div class="btn-group">'
                                + '<button class="btn btn-success btn-sm edit-button"><i class="fa fa-pencil" title="edit"></i></button>'
                                + '</div></div>';
                        }
                    },
                    {
                        sortable: false,
                        width: 60,
                        mRender: function (data, type, row) {
                            return '<div class="d-flex justify-content-center"><div class="btn-group">'
                                + '<button class="btn btn-primary btn-sm play-menu-button" type="button" onclick="playEnglishRecording(' + row['recording_id']  + ',\'' + row['name'] + '\')"><i class="fa fa-play"></i></button>'
                                + '</div></div>';
                        }
                    },
                    {
                        sortable: false,
                        width: 60,
                        mRender: function (data, type, row) {
                            return '<div class="d-flex justify-content-center"><div class="btn-group">'
                                + '<button class="btn btn-primary btn-sm play-menu-button" type="button" onclick="playHebrewRecording(' + row['id']  + ',\'' + row['name'] + '\')"><i class="fa fa-play"></i></button>'
                                + '</div></div>';
                        }
                    },
                ]
            });

            $('#audio_datatable').on('click', '.edit-button', async function () {
                console.log('edit-button');
                var audio = audioTable.row($(this).parents('tr')).data();
                RSApp.clearForm('edit_text_modal');
                audio_edit_id = audio.id;
                console.log(audio_edit_id);
                $('#edit_text_modal_id').val(audio.id);
                $('#edit_text_modal_tts_text').val(audio.tts_text);

                $('#edit_text_modal').modal('show');
            });

            $('#edit_text_modal_form').submit(async function(e) {
                $('#edit_text_modal_alert_danger').fadeOut();
                $('#edit_text_modal_alert_success').fadeOut();

                if(!e.isDefaultPrevented()){
                    e.preventDefault();
                    RSApp.jsonPost("{{route('hebrew_audio_edit')}}/" + audio_edit_id, this, false, function() {
                        $('#edit_text_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#edit_text_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', 'TTS Text edited', 'success', false);
                                RSApp.clearForm('edit_text_modal');
                                audioTable.ajax.reload();
                            } else {
                                $('#edit_text_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown){
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'edit_text_modal_');
                            } else {
                                $('#edit_text_modal_alert_danger').html('{{ trans('general.error.sending_request') }}' + ": " + errorThrown).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#edit_text_modal_form :input').removeAttr('disabled');
                        });
                }
            });

            $(window).keydown(function (e) {
                if (e.which == 33 || e.which == 34) {
                    e.preventDefault();
                }
            });
        })(jQuery)
    </script>

@stop