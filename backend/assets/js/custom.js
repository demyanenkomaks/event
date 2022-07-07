$(document).on('dblclick', '.grid-view tr', function () {
    var a = $(this).find('a[href*="view"]');
    if (!a.length) {
        a = $(this).find('a[href*="update"]');
    }
    a[0].click();
});

$(document).on('click', '.js-batch-action', function (e) {
    e.preventDefault();
    var $this = $(this);
    var url = $this.attr('href');
    url += (url.indexOf('?') != -1 ? '&id=' : '?id=');
    var ids = $('.grid-view').yiiGridView('getSelectedRows');
    url += ids.join(',');
    if (ids.length) {
        yii.confirm($this.data('message'), function () {
            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', url);
            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = $('meta[name=csrf-param]').attr('content');
            csrfInput.value = $('meta[name=csrf-token]').attr('content');
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        });
    }
});

$(document).on('change', '.js-batch-checkbox', function () {
    var $this = $(this);
    var buttons = $('.js-batch-action');
    var checkboxes = $('.js-batch-checkbox');
    var isChecked = false;

    checkboxes.each(function (i, checkbox) {
        if ($(checkbox).is(':checked')) {
            isChecked = true;
        }
    });

    if (isChecked) {
        buttons.removeClass('hidden');
    } else {
        buttons.addClass('hidden');
    }
});

if ($('.js-grid-sortable').length) {
    var handle = '.js-handle';
    var fixHelper = function (e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };

    var onSortStop = function (e, ui) {
        var data = [];
        ui.item.parent().find('tr').each(function (i, item) {
            data.push({
                id: $(item).data('id'),
                order: i
            });
        });
        $.ajax({
            url: this.parentElement.getAttribute('data-sort'),
            data: {sort: data},
            method: 'post',
            success: function (r) {
                $.pjax.reload('#grid-sortable-container').then(function () {
                    $('.js-grid-sortable tbody').sortable({
                        helper: fixHelper,
                        stop: onSortStop,
                        handle: handle,
                    });
                });
                if (r.success) {
                    $.toast({
                        text: "Порядок записей изменен",
                        position: "top-center",
                        icon: "success",
                        hideAfter: 15000,
                        stack: 15
                    });
                } else {
                    $.toast({
                        text: "Ошибка сортировки",
                        position: "top-center",
                        icon: "error",
                        hideAfter: 15000,
                        stack: 15
                    });
                }
            }
        });
    }

    $('.js-grid-sortable tbody').sortable({
        helper: fixHelper,
        stop: onSortStop,
        handle: handle,
    });
}
