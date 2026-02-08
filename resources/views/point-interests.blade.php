@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.point_interests'), 'route' => route('point_interests'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.point_interests'))

@section('css')
    <link href="{{ asset('/plugins/tables/css/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <style>
        #map {
            height: 100%;
        }

        #description {
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
            z-index: 1030 !important;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            z-index: 1040 !important;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        .pac-container {
            z-index: 1060 !important;
        }

        .modal {
            z-index: 1010 !important;
        }

        .modal-backdrop {
            z-index: 1000 !important;
        }
    </style>
@stop

@php($user = Auth::user())

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="point-accordion">
                    <div class="card-body">
                        @include('partials.point-accordion', ['points' => $points])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @component('components.modal', ['set_default' => true,
                                    'modal_id' => 'add_item_modal',
                                    'form' => true,
                                    'modal_title' => 'Add Item',
                                    'button_text' => trans('general.save')])
        <input name="parent_id" type="hidden" id="add_item_modal_parent_id">
        <input name="category" type="hidden" id="add_item_modal_category">
        <input name="map_lat" type="hidden" id="add_item_modal_map_lat">
        <input name="map_lng" type="hidden" id="add_item_modal_map_lng">
        <input name="map_place_name" type="hidden" id="add_item_modal_map_place_name">
        <input name="map_address" type="hidden" id="add_item_modal_map_address">
        <input name="id" type="hidden" id="add_item_modal_id">

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_item_modal_name">{{trans('criteria.name')}}</label>
                    <input name="name" type="text" class="form-control" id="add_item_modal_name"
                           placeholder="{{trans('criteria.enter.name')}}">
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group mb-1">
                    <label for="add_item_modal_menu_order">{{trans('criteria.menu_order')}}</label>
                    <input name="menu_order" type="text" class="form-control" id="add_item_modal_menu_order"
                           placeholder="{{trans('criteria.enter.menu_order')}}">
                    <div class="text-danger"></div>
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-1 form-group-check">
                    <div class="form-check" id="add_item_modal_active">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="add_item_modal_active_input"
                                   name="active" value="1"> {{__('general.active')}}</label>
                    </div>
                    <div class="text-danger"></div>
                </div>
            </div>
        </div>

        <div id="map_container" style="height: 50vh; z-index: 1020 !important;">
            <div class="pac-card" id="pac-card">
                <div>
                    <div id="title">Search Location</div>
                    <div id="type-selector" class="pac-controls">
                        <input type="radio" name="type" id="changetype-all" checked="checked"/>
                        <label for="changetype-all">All</label>

                        <input type="radio" name="type" id="changetype-establishment"/>
                        <label for="changetype-establishment">Establishments</label>

                        <input type="radio" name="type" id="changetype-address"/>
                        <label for="changetype-address">Addresses</label>

                        <input type="radio" name="type" id="changetype-geocode"/>
                        <label for="changetype-geocode">Geocodes</label>
                    </div>
                    <div id="strict-bounds-selector" class="pac-controls">
                        <input type="checkbox" id="use-strict-bounds" value=""/>
                        <label for="use-strict-bounds">Strict Bounds</label>
                    </div>
                </div>
                <div id="pac-container">
                    <input id="pac-input" type="text" placeholder="Enter a location"/>
                </div>
            </div>
            <div id="map"></div>
            <div id="infowindow-content">
                <img src="" width="16" height="16" id="place-icon" style="display:none"/>
                <p id="place-name" class="font-weight-bold mb-2"></p>
                <p id="place-address" class="mb-0"></p>
            </div>
        </div>
    @endcomponent

    @component('components.modal', ['modal_id' => 'delete_item_modal',
                                    'modal_class' => 'modal-danger',
                                    'modal_title' => 'Remove Item',
                                    'button_class' => 'btn-danger',
                                    'button_text' => trans('general.delete')])
    @endcomponent
@stop

@section('scripts')
    <script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('/js/rs-app.js') }}"></script>
    <script src="{{ google_script_api_url('setupMap') }}" defer></script>

    <script>
        var map, marker, infoWindowContent, infoWindow;

        function setupClickListener(id, types) {
            const radioButton = document.getElementById(id);
            radioButton.addEventListener("click", () => {
                autocomplete.setTypes(types);
            });
        }

        function setSavedMaker() {
            infoWindow.close();
            marker.setVisible(false);
            $('#map_container').css({'display': 'block'});

            const address = $('#add_item_modal_map_address').val();
            const name = $('#add_item_modal_map_place_name').val();
            const lat = $('#add_item_modal_map_lat').val();
            const lng = $('#add_item_modal_map_lng').val();

            if (address && name && lat && lng) {
                const latLng = new google.maps.LatLng(lat, lng);
                map.setCenter(latLng);
                map.setZoom(17);
                setMarker(address, name, '', latLng);
            }
        }

        function setMarker(address, name, icon, latLng) {
            marker.setPosition(latLng);
            marker.setVisible(true);
            infoWindowContent.children["place-icon"].src = icon;
            infoWindowContent.children["place-name"].textContent = name;
            infoWindowContent.children["place-address"].textContent = address;
            infoWindow.open(map, marker);
        }

        function setupMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {lat: 40.67777723390016, lng: -73.94568588317526},
                zoom: 13,
            });

            const card = document.getElementById("pac-card");
            const input = document.getElementById("pac-input");
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo("bounds", map);
            autocomplete.setFields(["address_components", "geometry", "icon", "name"]);

            infoWindow = new google.maps.InfoWindow();
            infoWindowContent = document.getElementById("infowindow-content");
            infoWindow.setContent(infoWindowContent);

            marker = new google.maps.Marker({map, anchorPoint: new google.maps.Point(0, -29)});

            autocomplete.addListener("place_changed", function () {
                infoWindow.close();
                marker.setVisible(false);
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                const address = (place.address_components ? [
                    (place.address_components[0] && place.address_components[0].short_name) || '',
                    (place.address_components[1] && place.address_components[1].short_name) || '',
                    (place.address_components[2] && place.address_components[2].short_name) || '',
                ].join(' ') : '');

                $('#add_item_modal_map_address').val(address);
                $('#add_item_modal_map_place_name').val(place.name);
                $('#add_item_modal_map_lat').val(place.geometry.location.lat());
                $('#add_item_modal_map_lng').val(place.geometry.location.lng());

                setMarker(address, place.name, place.icon, place.geometry.location);
            });

            setupClickListener("changetype-all", []);
            setupClickListener("changetype-address", ["address"]);
            setupClickListener("changetype-establishment", ["establishment"]);
            setupClickListener("changetype-geocode", ["geocode"]);

            document
                .getElementById("use-strict-bounds")
                .addEventListener("click", function () {
                    autocomplete.setOptions({strictBounds: this.checked});
                });
        }

        function addItem(parent_id, category, menu_order) {
            const item = {category, parent_id, menu_order, active: true};
            switch (item.category) {
                case 'menu':
                    $('#add_item_modal_modal_label').text('{{__('point_interests.add_main_menu')}}');
                    break;
                case 'category':
                    $('#add_item_modal_modal_label').text('{{__('point_interests.add_category')}}');
                    break;
                case 'point':
                //
                default:
                    $('#add_item_modal_modal_label').text('{{__('point_interests.add_point')}}');
                    break;
            }
            addEditItem(item);
        }

        function editItem(json) {
            const item = JSON.parse(json);
            switch (item.category) {
                case 'menu':
                    $('#add_item_modal_modal_label').text('{{__('point_interests.edit_main_menu')}}');
                    break;
                case 'category':
                    $('#add_item_modal_modal_label').text('{{__('point_interests.edit_category')}}');
                    break;
                case 'point':
                //
                default:
                    $('#add_item_modal_modal_label').text('{{__('point_interests.edit_point')}}');
                    break;
            }
            addEditItem(item);
        }

        function addEditItem(item) {
            RSApp.clearForm('add_item_modal');
            $('#add_item_modal .modal-dialog ').attr('class', 'modal-dialog');
            $('#map_container').css({'display': 'none'});
            switch (item.category) {
                case 'menu':
                    //
                    break;
                case 'category':
                    //
                    break;
                case 'point':
                    $('#add_item_modal .modal-dialog ').attr('class', 'modal-dialog modal-lg');
                    $('#add_item_modal_map_lat').val(item.map_lat || '');
                    $('#add_item_modal_map_lng').val(item.map_lng || '');
                    $('#add_item_modal_map_place_name').val(item.map_place_name || '');
                    $('#add_item_modal_map_address').val(item.map_address || '');
                    setSavedMaker();
                default:
                    //
                    break;
            }
            if (item.active) {
                $('#add_item_modal_active_input').prop("checked", "checked").trigger('change');
            }
            $('#add_item_modal_name').val(item.name || '');
            $('#add_item_modal_menu_order').val(item.menu_order);
            $('#add_item_modal_parent_id').val(item.parent_id);
            $('#add_item_modal_id').val(item.id || '');
            $('#add_item_modal_category').val(item.category);
            $('#add_item_modal').modal('show');
        }

        function deleteItem(id, name, category) {
            const confirmText = '{{__('point_interests.please_confirm')}}';
            $('#delete_item_modal_button').data('item-id', id);
            let html = '<h4>' + confirmText.replace(':itemName', name) + '</h4>';
            switch (category) {
                case 'menu':
                    html += '<p class="m-0"><strong>{{__('point_interests.confirm_menu')}}</strong></p>';
                    $('#delete_item_modal_modal_label').text('{{__('point_interests.remove_main_menu')}}');
                    break;
                case 'category':
                    html += '<p class="m-0"><strong>{{__('point_interests.confirm_category')}}</strong></p>';
                    $('#delete_item_modal_modal_label').text('{{__('point_interests.remove_category')}}');
                    break;
                case 'point':
                default:
                    html += '<p class="m-0"><strong>{{__('point_interests.confirm_point')}}</strong></p>';
                    $('#delete_item_modal_modal_label').text('{{__('point_interests.remove_point')}}');
                    break;
            }
            html += '<p class="m-0"><strong>ONCE DELETED IT CANNOT BE RESTORED.</strong></p>';
            $('#delete_item_modal').find('.modal-body').html(html);
            $('#delete_item_modal').modal('show');
        }

        function collapseItems() {
            RSApp.jsonGet('{{route('point_interests_show', ['id' => 0])}}')
                .done(function (data) {
                    const items = $('[id^="row-point-"] [data-toggle="collapse"]');
                    $('#point-accordion .card-body').html(data.render);
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
            $('#delete_item_modal_button').click(function () {
                $('#delete_item_modal').modal('hide');
                RSApp.jsonPost("{{ route('point_interest_delete', ['id' => ':id']) }}".replace(':id', $(this).data('item-id'))).done(function (data) {
                    if (data.done) {
                        RSApp.alert('{{ trans('general.done') }}', '{{__('point_interests.item_deleted')}}', 'success', false);
                        collapseItems();
                    } else {
                        RSApp.alert('{{ trans('general.error.error') }}:', data.error, 'warning');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    RSApp.alert('{{ trans('general.error.sending_request') }}:', errorThrown, 'danger');
                });
            });

            $('#add_item_modal_form').submit(async function (e) {
                $('#add_item_modal_alert_danger').fadeOut();
                $('#add_item_modal_alert_success').fadeOut();

                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    RSApp.jsonPost("{{route('point_interest_add_edit')}}", this, false, function () {
                        $('#add_item_modal_form :input').attr('disabled', 'disabled');
                    })
                        .done(function (data) {
                            if (data.done) {
                                $('#add_item_modal').modal('hide');
                                RSApp.alert('{{ trans('general.done') }}', '{{__('point_interests.item_saved')}}', 'success', false);
                                RSApp.clearForm('add_item_modal');
                                collapseItems();
                            } else {
                                $('#add_item_modal_alert_danger').html(data.error).fadeIn();
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status == 422) {
                                RSApp.inputValidation(jqXHR['responseJSON'], 'add_item_modal_');
                            } else {
                                $('#add_item_modal_alert_danger').html(jqXHR.responseText).fadeIn();
                            }
                        })
                        .always(function () {
                            $('#add_item_modal_form :input').removeAttr('disabled');
                        });
                }
            });
        })();
    </script>
@stop
