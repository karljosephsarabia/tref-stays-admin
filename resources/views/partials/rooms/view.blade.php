@if($view == 'grid')
    @include('partials.rooms.grid')
@elseif($view == 'map')
    @include('partials.rooms.map')
@else
    <div class="pt-5">
        <h1 class="text-muted text-center mt-5">
            <i class="fa fa-eye-slash"></i> {{trans('general.view_undefined')}}
        </h1>
    </div>
@endif

@section('rooms_scripts')
    <script>
        window.template = {
            'item_image': '<img class="img-fluid img-carousel" src="__href__" alt="">',
            'item_carousel': '<div class="carousel slide" id="carousel___id__">\n' +
                '    <ol class="carousel-indicators">__carousel_li_items__</ol>\n' +
                '    <div class="carousel-inner">__images_items__</div>\n' +
                '    <a data-slide="prev" href="#carousel___id__" class="carousel-control-prev">\n' +
                '        <span class="carousel-control-prev-icon"></span>\n' +
                '        <span class="sr-only">{{trans('pagination.previous_1')}}</span>\n' +
                '    </a>\n' +
                '    <a data-slide="next" href="#carousel___id__" class="carousel-control-next">\n' +
                '        <span class="carousel-control-next-icon"></span>\n' +
                '        <span class="sr-only">{{trans('pagination.next_1')}}</span>\n' +
                '    </a>\n' +
                '</div>',
            'carousel_li': '<li data-slide-to="__index__" data-target="#carousel___id__" class="__active__"></li>',
            'carousel_image': '<div class="carousel-item __active__">' +
                '   <img class="d-block w-100 img-carousel" src="__href__" alt="" />' +
                '</div>',
            'card': '<div class="card card-hover__card_info__ __m_card__">\n' +
                '        __images__\n' +
                '        <div class="card-body p-__p_body__">\n' +
                '            <a class="no-hover" target="_blank" href="__href__">\n' +
                '                <p class="card-text mb-1">\n' +
                '                    <span class="text-muted">__type_location__</span>\n' +
                '                </p>\n' +
                '                <h5 class="card-title mb-1">__title__</h5>\n' +
                '                <p class="card-text mb-1">__specs__</p>\n' +
                '                <p class="card-text text-justify">__luxury__</p>\n' +
                '            </a>\n' +
                '        </div>\n' +
                '        <div class="card-footer __p_footer__">\n' +
                '            <a class="no-hover" target="_blank" href="__href__">\n' +
                '               <p class="card-text text-right font-weight-bold">\n' +
                '                   $ <span class="text-dark">__price__</span> <small><?php echo e(trans('reservation.per_night')); ?></small>\n' +
                '               </p>\n' +
                '            </a>\n' +
                '        </div>\n' +
                '</div>\n'
        };
        window.template['map'] = {
            'id': 'map_property_items',
            'item': '<li class="media search-media" id="media_property___id__" ' +
                'onmouseover="propertyMouse(\'popup_property___id__\', \'over\')" ' +
                'onmouseout="propertyMouse(\'popup_property___id__\', \'out\')">\n' +
                '   <div class="mr-2 search-carousel">__images__</div>\n' +
                '   <div class="media-body mr-1">\n' +
                '       <a class="no-hover" target="_blank" href="__href__">\n' +
                '           <p class="card-text mb-1"><span class="text-muted">__type_location__</span></p>\n' +
                '           <h5 class="card-title mb-1">__title__</h5>\n' +
                '           <div style="height: 133px;">\n' +
                '               <p class="card-text mb-1">__specs__</p>\n' +
                '               <p class="card-text text-justify">__luxury__</p>\n' +
                '           </div>\n' +
                '           <p class="card-text text-right font-weight-bold">\n' +
                '               $ <span class="text-dark">__price__</span> <small>{{trans('reservation.per_night')}}</small>\n' +
                '           </p>\n' +
                '       </a>\n' +
                '   </div>\n' +
                '</li>',
            'no_records': '<div class="not-found">\n' +
                '   <h1 class="text-muted text-center">\n' +
                '       <i class="fa fa-frown-o"></i> {{trans('general.no_records')}}\n' +
                '   </h1>' +
                '</div>'
        };
        window.template['grid'] = {
            'id': 'grid_property_items',
            'item': '<div class="col-md-6 col-lg-3">' + window.template['card'] + '</div>',
            'no_records': '<div class="pt-5">\n' +
                '   <h1 class="text-muted text-center mt-5">\n' +
                '       <i class="fa fa-frown-o"></i> {{trans('general.no_records')}}\n' +
                '   </h1>\n' +
                '</div>'
        }

        function loadProperties(templateName, map, form, search) {
            const parentElement = $('#' + window.template[templateName]['id']);

            RSApp.jsonPost('{{route('rooms')}}', form, false, function () {
                $('form#form_search_bar :input').attr('disabled', 'disabled');
                parentElement.html('<div class="loader spinner-2"></div>');

                if (map) {
                    removeMarkers();
                }
            })
                .done(function (data) {
                    if (data.done) {
                        if (data.data.length > 0) {
                            if (map) {
                                displayMarkers(data.data, search);
                            } else {
                                displayList(data.data, parentElement, templateName, !map, search);
                            }
                        } else {
                            parentElement.html(window.template[templateName]['no_records']);
                        }
                    } else {
                        console.log(data.error);
                        parentElement.html(window.template[templateName]['no_records']);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    parentElement.html(window.template[templateName]['no_records']);
                })
                .always(function () {
                    $('form#form_search_bar :input').removeAttr('disabled');
                });
        }

        function propertyImages(property) {
            const images = property['images'] || [];

            if (images.length > 0) {
                if (images.length > 1) {
                    let liItems = '';
                    let carouselImages = '';

                    images.forEach(function (item, i) {
                        liItems += window.template['carousel_li']
                            .replace(/__index__/g, i)
                            .replace(/__active__/g, i == 0 ? 'active' : '');

                        carouselImages += window.template['carousel_image']
                            .replace(/__active__/g, i == 0 ? 'active' : '')
                            .replace(/__href__/g, item);
                    });

                    return window.template['item_carousel']
                        .replace(/__carousel_li_items__/g, liItems)
                        .replace(/__images_items__/g, carouselImages);

                } else {
                    return window.template['item_image'].replace(/__href__/g, images[0]);
                }
            }

            return window.template['item_image']
                .replace(/__href__/g, property['map_image']);
        }

        function displayList(properties, parentElement, templateName, grid = false, search = '') {
            let items = grid ? '<div class="row">' : '';

            properties.forEach(function (item) {
                items += createPropertyInfo(window.template[templateName]['item'], item, search);
            });

            items += grid ? '</div>' : '';

            parentElement.html(items);
        }

        function createPropertyInfo(template, item, search = '', info = false) {
            return template
                .replace(/__title__/g, item['title'])
                .replace(/__href__/g, item['route'] + search)
                .replace(/__price__/g, formatCurrency(item['price']))
                .replace(/__luxury__/g, item['luxury'] || '')
                .replace(/__specs__/g, item['specs'])
                .replace(/__type_location__/g, item['type_location'])
                .replace(/__images__/g, propertyImages(item))
                .replace(/__id__/g, item['id'])
                .replace(/__p_body__/g, info ? '2' : '4')
                .replace(/__p_footer__/g, info ? 'p-2' : '')
                .replace(/__m_card__/g, info ? 'm-0' : '')
                .replace(/__card_info__/g, info ? '_info' : '');
        }
    </script>
@stop