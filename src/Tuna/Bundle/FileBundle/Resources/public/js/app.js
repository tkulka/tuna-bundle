window.tuna.file = {
    view: {}
};

tuna.file.initFileBundle = function (tunaEvents) {
    new tuna.file.view.QueueView({
        el: $('#upload'),
        tunaEvents: tunaEvents
    });

    $('[data-dropzone-options]').each(function (index, item) {
        var options = $(item).data('dropzone-options');
        var $dropzonable = options.selector ? $(options.selector) : $(item);

        if (!$dropzonable.data('dropover-text')) {
            new tuna.file.view.DropzoneView({
                el: $dropzonable,
                options: options,
                tunaEvents: tunaEvents
            });
        }
    });
};
