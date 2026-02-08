<div id="grid_property_items"></div>

@section('room_scripts')
    <script>
        (function ($) {
            $('form#form_search_bar').on('submit', function (e) {
                if ($(this).data('submit')) return;
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    loadProperties('grid', false, $(this), '?' + $.param(RSApp.formJsonData($(this), false)));
                }
            });

            loadProperties('grid', false, $('form#form_search_bar'), window.location.search);
        })(jQuery);
    </script>
@stop
