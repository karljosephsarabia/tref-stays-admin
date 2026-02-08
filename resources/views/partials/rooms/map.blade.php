@component('components.card')
    @slot('body')
        <div class="row">
            <div class="col-12" style="height:700px" id="map_result"></div>
        </div>
    @endslot
@endcomponent

@section('room_scripts')
    <script src="{{ asset('/js/popup.js') }}"></script>
    <script>
        window.map = null;
        let Popup, markers = [];

        (function ($) {
            $('form#form_search_bar').on('submit', function (e) {
                if ($(this).data('submit')) return;
                e.preventDefault();
                if (e.isDefaultPrevented) {
                    loadProperties('map', true, $(this), '?' + $.param(RSApp.formJsonData($(this), false)));
                }
            });

            loadProperties('map', true, $('form#form_search_bar'), window.location.search);
        })(jQuery);

        function propertyMouse(itemId, action) {
            const element = $('#' + itemId);

            if (action == 'over') {
                if (!element.hasClass('hovered')) {
                    element.addClass('hovered')
                }
            }

            if (action == 'out') {
                if (element.hasClass('hovered')) {
                    element.removeClass('hovered');
                }
            }
        }

        function initMap() {
            Popup = createPopupClass();

            window.map = new google.maps.Map(document.getElementById('map_result'), {
                center: {lat: 40.7077585, lng: -74.00885029999999},
                zoom: 17,
                options: {
                    gestureHandling: 'greedy'
                },
                clickableIcons: false,
                scaleControl: false,
                fullscreenControl: false,
                mapTypeControl: false,
                streetViewControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            /*if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    window.map.setCenter({
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    });
                }, function (error) {
                    console.log(error);
                });
            } else {
                console.log('Browser doesn\'t support Geolocation');
            }*/

            google.maps.event.addListener(window.map, 'click', function (event) {
                if (event.placeId) {
                    placeDetails(event.placeId);
                }
            });
        }

        function removeMarkers() {
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];
        }

        function displayMarkers(properties, search = '') {
            const bounds = new google.maps.LatLngBounds();

            properties.forEach(async function (item) {

                const markerPopup = document.createElement('div');
                markerPopup.title = item['map_address'];

                const markerLink = document.createElement('a');
                markerLink.innerText = '$' + item['price'];
                markerLink.onclick = function () {
                    $('div[id^="property_info_"]').css({display: 'none'});
                    $('#property_info_' + item['id']).css({display: 'block'});
                };
                markerLink.style.cssText = 'color:inherit;transition:none;cursor:pointer;';

                markerPopup.append(markerLink);

                if ((item['map_lat'] == '' || item['map_lat'] == null) || (item['map_lng'] == '' || item['map_lng'] == null)) {
                    await propertyPosition(item);
                }

                const markerLocation = new google.maps.LatLng(+item['map_lat'], +item['map_lng']);

                const marker = new Popup(markerLocation, markerPopup);

                marker.containerDiv.id = 'popup_property_' + item['id'];
                marker.containerDiv.onmouseout = function () {
                    propertyMouse('media_property_' + item['id'], 'out');
                }

                marker.containerDiv.onmouseover = function () {
                    propertyMouse('media_property_' + item['id'], 'over');
                }

                marker.containerDiv.append(setPropertyPopUpper(item, search));

                marker.setMap(window.map);

                markers.push(marker);

                bounds.extend(markerLocation);

            });

            window.map.fitBounds(bounds);
        }

        function setPropertyPopUpper(property, search = '') {
            let element = $('<div>' + createPropertyInfo(window.template['card'], property, search, true) + '</div>');

            element.attr('id', 'property_info_' + property['id']);
            element.css({display: 'none', width: '250px', position: 'absolute', left: 'calc(50% - 225px)', top: '5px'});

            return element[0];
        }

        function propertyPosition(property) {
            const address = property.map_address;

            return new Promise(function (resolve) {
                new google.maps.Geocoder().geocode({address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        property['lat'] = results[0].geometry.location.lat();
                        property['lng'] = results[0].geometry.location.lng();
                        property['map_address'] = results[0].formatted_address;

                        const result = {
                            lat: property['lat'],
                            lng: property['lng'],
                            address: property['map_address']
                        };

                        resolve({status});
                        RSApp.jsonPost('{{route('set_map_location')}}/' + property.id, result, true)
                            .done(function (data) {
                                console.log(data);
                            })
                            .fail(function (a, b, c) {
                                console.log(c);
                            });
                    } else {
                        resolve({status});
                    }
                });
            });
        }

        function placeDetails(placeId) {
            const request = {
                placeId,
                fields: [
                    'address_component',
                    'name',
                    'type'
                ]
            };

            new google.maps.places.PlacesService(window.map)
                .getDetails(request, function (place, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        const address_parts = place.address_components.filter(function (item) {
                            return item.types.includes('political');
                        });
                        console.log(place.name, address_parts);
                    }
                });
        }
    </script>

    <script src="{{google_script_api_url('initMap')}}" async defer></script>
@stop