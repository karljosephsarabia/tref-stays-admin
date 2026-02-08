/**
 * Checking we got Jquery defined.
 */
if (typeof jQuery === "undefined") {
    throw new Error("RSApp requires jQuery");
}

/**
 * Let's define RSApp
 */
(function (RSApp, $, window, undefined) {

    if (typeof $.fn.dataTable !== "undefined") {
        if (typeof $.fn.dataTable.ext !== "undefined") {
            $.fn.dataTable.ext.errMode = function (settings, techNote, message) {
                RSApp.alert('ERR:', 'DT-ERROR-' + techNote, 'danger', true);
                console.log(message);
            };
        }

        if (typeof $.fn.dataTable.Api !== "undefined") {
            $.fn.dataTable.Api.register('autoHeight', function () {
                var $dt = this;
                this.on('init', function () {
                    var $sb = $($($dt).context[0].nScrollBody);
                    var tsh = $sb.height();
                    var avail = $(window).height() - ($('section.content').position().top + $('section.content').height());
                    h = tsh + avail - 50;
                    $sb.css('height', h > 100 ? h : 100);
                    $(window).resize(function () {
                        tsh = $sb.height();
                        avail = $(window).height() - ($('section.content').position().top + $('section.content').height());
                        var h = tsh + avail - 50;
                        $sb.css('height', h > 100 ? h : 100);
                    });
                });
            });

            $.fn.dataTable.Api.register('columnsSearch', function () {
                var $dt = this;
                this.on('init', function () {
                    $dt.columns().every(function () {
                        var $col = this;
                        var $header = $($col.header());
                        if ($header.hasClass('searchable')) {
                            var $title = $header.text();
                            $header.html($title + '<br /><input type="text" class="table-search" placeholder="Search" />');
                            $('input', $col.header()).on('click', function (event) {
                                event.stopPropagation();
                            });
                            $('input', $col.header()).on('keyup change', function () {
                                if ($col.search() !== this.value) {
                                    var $search_text = this.value;
                                    clearTimeout($col.search_timeout_id);
                                    $col.search_timeout_id = setTimeout(function () {
                                        $col.search($search_text, false, false, true).draw();
                                    }, $search_text.length < 8 ? 500 : 250);
                                }
                            });
                        }
                    });
                });
            });
        }
    }

    RSApp.getCSFR = function () {
        return $('meta[name="csrf-token"]').attr('content');
    };

    RSApp.clearForm = function (section) {
        $('#' + section + '_form')[0].reset();
        $('#' + section + '_alert_danger').html('').fadeOut();
        $('#' + section + '_alert_success').html('').fadeOut();
        $.each($('#' + section + '_form' + ' :input:not(:button):not([type="checkbox"])'), function (i, item) {
            $('#' + item.id).val('').trigger('change');
        });
    }

    RSApp.formJsonData = function (form, json = true) {
        var formData = null;
        var jsonObject = {};
        if ($.type(form) === "string") {
            if ($(form).length == 0) {
                formData = form;
            } else {
                formData = $(form);
            }
        } else if ($.type(form) === "object") {
            if (form instanceof $) {
                formData = form;
            } else {
                formData = $(form);
            }
        }
        if (formData instanceof $) {
            formData.serializeArray().map(function (item) {
                if (jsonObject[item.name]) {
                    if (typeof (jsonObject[item.name]) === "string") {
                        jsonObject[item.name] = [jsonObject[item.name]];
                    }
                    jsonObject[item.name].push(item.value);
                } else {
                    jsonObject[item.name] = item.value;
                }
            });
            jsonObject = json ? JSON.stringify(jsonObject) : jsonObject;
        }
        return jsonObject;
    };

    RSApp.jsonGet = function (url, beforeSend = null) {
        return $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend
        });
    };

    RSApp.jsonPost = function (url, form, json = false, beforeSend = null) {
        return $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': RSApp.getCSFR()},
            url: url,
            data: json ? JSON.stringify(form) : RSApp.formJsonData(form),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend
        });
    };

    RSApp.formPost = function (url, form, beforeSend = null) {
        return $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': RSApp.getCSFR()},
            url: url,
            data: new FormData($(form)[0]),
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend
        });
    };

    RSApp.alert = function (title, message, type, no_close) {
        var icon = 'fa fa-check';
        if (type == 'danger') {
            icon = 'fa fa-ban';
        }
        if (type == 'warning') {
            icon = 'fa fa-warning';
        }
        if (type == 'info') {
            icon = 'fa fa-info';
        }
        return $.notify({
            title: ' <strong>' + title + '</strong>',
            icon: icon,
            message: message
        }, {
            type: type,
            newest_on_top: true,
            allow_dismiss: true,
            mouse_over: "pause",
            delay: no_close ? 0 : 10000,
            placement: {
                from: "top",
                align: "center"
            },
            animate: {
                enter: 'animated fadeInRight',
                exit: 'animated fadeOutRight'
            }
        });
    };

    RSApp.inputValidation = function (errors, modalId) {
        let tabActive = null;
        let focusInput = null;
        Object.keys(errors).forEach(function (key) {
            const input = $('#' + modalId + key);
            focusInput = focusInput || input;
            input.siblings('div.text-danger').text(errors[key][0]);
            tabActive = tabActive || input.data('tab');
        });
        if (tabActive) {
            $('a[href="#' + modalId + 'tab_' + tabActive + '"]')
                .tab('show')
                .on('shown.bs.tab', function (e) {
                    focusInput.focus();
                });
        }
    };

    RSApp.resetFormValidation = function (formId, setDefault) {
        $.each($('#' + formId + ' :input:not(:button)'), function (i, item) {
            RSApp.resetInputValidation(item.id, setDefault || 0);
        });
    }

    RSApp.resetInputValidation = function (inputId, setDefault) {
        if (inputId != '') {
            const input = $('#' + inputId);
            if (input.data('default') && (setDefault || 0)) {
                input.val(input.data('default')).trigger('change');
            }
            input.siblings('div.text-danger').text('');
        }
    }

    RSApp.addOptionToSelect2 = function (elementId, data, clear = false) {
        if (clear) {
            $(elementId).html('');
        }
        $.each($.isArray(data) ? data : [].concat(data), function (index, item){
            if ($(elementId).find("option[value='" + item.id + "']").length) {
                $(elementId).val(item.id).trigger('change');
            } else {
                $(elementId).append(new Option(item.text, item.id, true, true)).trigger('change');
            }
        });
    }

}(window.RSApp = window.RSApp || {}, jQuery, window));

(function ($) {
    $('[data-role="select2"], [role="select2"]').each(function (i, item) {
        const element = $(item);
        const data = element.data();

        const options = {
            templateResult: function (item) {
                if (item.code) return item.code + ' - ' + item.text;
                if (item.role) return item.text + ' (' + item.role + ')';
                return item.text;
            }
        };

        if (data['dropdownParent']) {
            options['dropdownParent'] = data['dropdownParent'];
        }

        if (data['allowClear']) {
            options['allowClear'] = true;
        }

        if (data['placeholder']) {
            options['placeholder'] = data['placeholder'];
        }

        if (data['minimumInput'] != undefined) {
            options['minimumInputLength'] = data['minimumInput'];
        }

        if (data['url']) {
            options['ajax'] = {
                delay: 750,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8'
            };

            options['ajax']['url'] = data['url'];

            options['ajax']['data'] = function (params) {
                const query = {
                    search: params.term,
                    all: data['minimumInput'] == 0,
                    key: data['searchKey'] || 'id'
                };

                if (data['foreign']) {
                    query['foreign'] = $(data['foreign']).val();
                }

                return query;
            };

            options['ajax']['processResults'] = function (d) {
                const result = {};

                if (d.done) {
                    const key = data['searchKey'] || 'id';
                    result['results'] = $.map(d.results, function (item, i) {
                        item['id'] = item[key];
                        return item;
                    });
                } else {
                    result['results'] = [];
                    console.log(d.error);
                }

                return result;
            };
        }

        element.select2(options);

        if (data['target']) {
            element.on('change', function (e) {
                const target = $($(this).data('target'));
                target.val(null).trigger('change');
            });
        }
    });

    $.getParams = function (key, data = '') {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has(key)) {
            return urlParams.get(key);
        }
        if (urlParams.has(key + '[]')) {
            return urlParams.getAll(key + '[]');
        }
        return data;
    }
})(jQuery);