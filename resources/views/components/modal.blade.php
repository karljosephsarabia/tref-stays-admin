<!-- Modal -->
@php
    $modal_class = $modal_class ?? '';
    $modal_size = $modal_size ?? '';
    $set_default = $set_default ?? false;
    $before_buttons = $before_buttons ?? '';
    $after_buttons = $after_buttons ?? '';
    $button_id = $button_id ?? $modal_id.'_button';
    $button_text = $button_text ?? trans('general.ok');
    $button_class = $button_class ?? 'btn-primary';
    $button_close = $button_close ?? false;
    $form = $form ?? false;
    $form_files = $form_files ?? false;
@endphp
@if( isset($form) && $form )
    <form id="{{ $modal_id }}_form" role="form"
          data-toggle="validator" {!! $form_files ? 'enctype="multipart/form-data"' : '' !!}>
        @endif
        <div class="modal fade {{ $modal_class }}" id="{{ $modal_id }}"
             tabindex="-1" role="dialog" aria-labelledby="{{ $modal_id }}_modal_label" data-set-default="{{ $set_default }}">
            <div class="modal-dialog {{ $modal_size }}" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="{{ $modal_id }}_modal_label">{{ $modal_title }}</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="{{ trans('general.close') }}"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if( isset($form) && $form )
                            <div id="{{ $modal_id }}_alert_danger" class="alert alert-danger" style="display: none;">
                            </div>
                            <div id="{{ $modal_id }}_alert_success" class="alert alert-success" style="display: none;">
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                    <div class="modal-footer">
                        {!! $before_buttons !!}
                        <button type="{{ $form ? 'submit':'button' }}"
                                class="btn {{ $button_class }}"
                                id="{{ $button_id }}" {!! $button_close ? 'data-dismiss="modal"':'' !!}>{{ $button_text }}</button>
                        {!! $after_buttons !!}
                    </div>
                </div>
            </div>
        </div>
        @if( isset($form) && $form )
    </form>
@endif