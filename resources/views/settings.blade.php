@extends('partials.layout', [
    'breadcrumb' => [
        'items' => [
            ['name' => trans('general.manager'), 'route' => 'javascript:void(0)', 'class' => ''],
            ['name' => trans('general.settings'), 'route' => route('setting'), 'class' => 'active']
        ]
    ]
])

@section('title', trans('general.settings'))

<!--


sidebarStyle: "full", //defines how sidebar should look like, options are: "full", "compact", "mini" and "overlay". If layout is "horizontal", sidebarStyle won't take "overlay" argument anymore, this will turn into "full" automatically!
sidebarBg: "color_1", //have 10 options, "color_1" to "color_10"
sidebarPosition: "static", //have two options, "static" and "fixed"
headerPosition: "static", //have two options, "static" and "fixed"
containerLayout: "wide",  //"boxed" and  "wide". If layout "vertical" and containerLayout "boxed", sidebarStyle will automatically turn into "overlay".
direction: "ltr" //"ltr" = Left to Right; "rtl" = Right to Left/*

-->

@section('content_body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Theme</h5>
                        <form id="app_setting" action="" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Version</label>
                                    <select class="form-control" name="version">
                                        <option value="light">Light</option>
                                        <option value="dark">Dark</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Layout</label>
                                    <select class="form-control" name="layout">
                                        <option value="vertical">Vertical</option>
                                        <option value="horizontal">Horizontal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>navheaderBg</label>
                                    <select class="form-control" name="navheaderBg">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="color_{{ $i }}">Color {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>headerBg</label>
                                    <select class="form-control" name="headerBg">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="color_{{ $i }}">Color {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>sidebarStyle</label>
                                    <select class="form-control" name="sidebarStyle">
                                        <option value="full">full</option>
                                        <option value="compact">compact</option>
                                        <option value="mini">mini</option>
                                        <option value="overlay">overlay</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>sidebarBg</label>
                                    <select class="form-control" name="sidebarBg">
                                        @for($i = 1; $i<=10; $i++)
                                            <option value="color_{{ $i }}">Color {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>sidebarPosition</label>
                                    <select class="form-control" name="sidebarPosition">
                                        <option value="static">static</option>
                                        <option value="fixed">fixed</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>headerPosition</label>
                                    <select class="form-control" name="headerPosition">
                                        <option value="static">static</option>
                                        <option value="fixed">fixed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>containerLayout</label>
                                    <select class="form-control" name="containerLayout">
                                        <option value="wide">wide</option>
                                        <option value="boxed">boxed</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>direction</label>
                                    <select class="form-control" name="direction">
                                        <option value="ltr">Left to Right</option>
                                        <option value="rtl">Right to Left</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--<div class="card-footer">
                        <a href="#" class="btn btn-primary float-right">Save</a>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        (function () {
            $('form#app_setting :input').on('change', function (e) {
                console.log($(this).attr('name'), $(this).val(), $('form#app_setting').serializeObject());
                new quixSettings($('form#app_setting').serializeObject());
            });

            for (let key of Object.keys(window.themeSetting)){
                $('[name="' + key + '"]').val(window.themeSetting[key]);
            }
        })(jQuery);
    </script>
@stop
