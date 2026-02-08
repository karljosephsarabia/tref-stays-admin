jQuery(window).on("load", function () {
    $('#preloader').fadeOut(500);
    $('#main-wrapper').addClass('show');
    $('body').attr('data-sidebar-style') === "mini" ? $(".hamburger").addClass('is-active') : $(".hamburger").removeClass('is-active')
});

(function ($) {
    "use strict";

    $('[role="dialog"]').on('show.bs.modal', function (e) {
        RSApp.resetFormValidation(this.id + '_form', $(this).data('set-default'));
        $.each($('#' + this.id + '_form :input[data-trigger]:not(:button)'), function (i, item) {
            $(item).trigger($(item).data('trigger'));
        });
        const firstTab = $($('#' + this.id + ' .modal-body [data-toggle="tab"]')).first();
        if (firstTab) {
            firstTab.tab('show');
        }
    });

    $('form:not(.login-form) :input:not(:button)').on('input', function (e) {
        RSApp.resetInputValidation(this.id);
    });

    $(window).keydown(function (e) {
        if (e.which == 33 || e.which == 34) {
            e.preventDefault();
        }
    });

    $('[role="combobox"]').on('change', function (e) {
        const target = $('#' + $(this).data('target'));

        if (target.data('data-key')) {
            const value = $(this).val();
            const defaultEmpty = target.data('default-empty');
            const options = (window[target.data('data-key')] || []).filter(function (item) {
                return item['foreign'] == value;
            });
            target.html('');
            if (options.length > 0) {
                if (defaultEmpty) {
                    target.append('<option value="">' + defaultEmpty + '</option>');
                }
                $.each(options, function (i, item) {
                    let option = '<option data-name="' + item.name + '" data-code="' + item.code + '" value="';
                    option += item.id + '">' + (item.code ? item.code + ' - ' + item.name : item.name) + '</option>'
                    target.append(option);
                });
            } else {
                target.append('<option value="">' + target.data('no-records') + '</option>');
            }
        }
        target.trigger('change');
    });

    $("#menu").metisMenu();

    $('.nk-nav-scroll').slimscroll({
        position: "right",
        size: "5px",
        height: "100%",
        color: "transparent"
    });

    $(".nav-control").on('click', function () {
        $('#main-wrapper').toggleClass("menu-toggle");
        $(".hamburger").toggleClass("is-active");
    });

    $(function () {
        for (var nk = window.location,
                 o = $("ul#menu a").filter(function () {
                     return this.href == nk;
                 })
                     .addClass("active")
                     .parent()
                     .addClass("active"); ;) {
            if (!o.is("li")) break;
            o = o.parent()
                .addClass("in")
                .parent()
                .addClass("active");
        }
    });

    $(function () {
        const win_h = window.document.body.offsetHeight;
        if (win_h > 0 ? win_h : window.screen.height) {
            const header = $('#main-wrapper .header').height();
            const footer = $('#main-wrapper .footer').height();
            $(".content-body").css("min-height", (win_h - header - footer) + "px");
        }
    });

    $('.selectpicker').selectpicker();
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    const headerHight = $('.header').innerHeight();

    $(window).scroll(function () {
        if ($('body').attr('data-layout') === "horizontal" && $('body').attr('data-header-position') === "static" && $('body').attr('data-sidebar-position') === "fixed")
            $(this.window).scrollTop() >= headerHight ? $('.metismenu').addClass('fixed') : $('.metismenu').removeClass('fixed')
    });

    $('.sidebar-right-trigger').on('click', function () {
        $('.sidebar-right').toggleClass('show');
    });
})(jQuery);
