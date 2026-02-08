<!DOCTYPE html>
<html class="h-100" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">

    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon.png') }}">

    <link href="{{asset('/plugins/bootstrap-daterangepicker2/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">

    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/reservationsystem.css') }}" rel="stylesheet">
</head>

<body>
<div id="main-wrapper">
    <div class="content-body" style="margin-left:0 !important;">
        <div class="container-fluid" style="padding:3px!important;">
            <div id="grid_property_items"></div>
        </div>
    </div>
</div>

<script src="{{ asset('/plugins/common/common.min.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>
<script src="{{ asset('/js/settings.js') }}"></script>
<script src="{{ asset('/js/styleSwitcher.js') }}"></script>

<script src="{{asset('/plugins/moment/moment.js')}}"></script>
<script src="{{ asset('/plugins/bootstrap-daterangepicker2/daterangepicker.js') }}"></script>
<script src="{{ asset('/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('/plugins/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/js/rs-app.js') }}"></script>

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
        'card': '<div class="card card-hover__card_info__ __m_card__" style="margin-bottom:1px!important;">\n' +
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
    window.template['grid'] = {
        'id': 'grid_property_items',
        'item': '<div class="col-md-12 col-lg-12" style="padding:3px!important;">' + window.template['card'] + '</div>',
        'no_records': '<div class="pt-5">\n' +
            '   <h1 class="text-muted text-center mt-5">\n' +
            '       <i class="fa fa-frown-o"></i> {{trans('general.no_records')}}\n' +
            '   </h1>\n' +
            '</div>'
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
            items += createPropertyInfo(window.template[templateName]['item'], item, search, false);
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

    $(document).ready(function(){
        var property = {!! json_encode($property, JSON_HEX_TAG) !!};
        const parentElement = $('#' + window.template['grid']['id']);
        var properties = [];
        properties[0] = property;
        console.log(properties);
        displayList(properties, parentElement, 'grid', false);
    });

    (function ($) {
        "use strict"
        new quixSettings(window.themeSetting);
    })(jQuery);

    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function formatCurrency(n) {
        var blur = "blur";
        // get input value
        var input_val = n + "";

        // don't validate empty input
        if (n === "") { return; }

        if (n === 0) {
            input_val += ".00";
            return input_val
        }

        // original length
        var original_len = input_val.length;

        if(original_len <= 0) { return; }

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        return input_val;
    }

</script>
</body>
</html>