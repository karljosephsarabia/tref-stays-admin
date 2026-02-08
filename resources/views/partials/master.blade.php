<!DOCTYPE html>
<html class="h-100" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    @yield('meta')
    <title>@yield('title') - {{config('app.name')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    @yield('css')
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/reservationsystem.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/tref-stays.css') }}" rel="stylesheet">
</head>

<body class="@yield('body_class')">
<div id="preloader">
    <div class="loader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
        </svg>
    </div>
</div>

@yield('body')

<script src="{{ asset('/plugins/common/common.min.js') }}"></script>
<script src="{{ asset('/js/custom.js') }}"></script>
<script src="{{ asset('/js/settings.js') }}"></script>
<script src="{{ asset('/js/styleSwitcher.js') }}"></script>

@yield('scripts')

<script>
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