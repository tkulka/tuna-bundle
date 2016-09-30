window.tuna.file = {
    events: _.extend({}, Backbone.Events),
    view: {}
};

tuna.file.initFileBundle = function(tunaEvents) {
    new tuna.file.view.QueueView({
        el: $('body'),
        tunaEvents: tunaEvents
    });

    $('[data-dropzone-options]').each(function (index, item) {
        var options = $(item).data('dropzone-options');
        var $selector = $(options.selector);

        if (!$selector.data('dropover-text')) {
            new tuna.file.view.DropzoneView({
                el: $selector,
                options: options,
                tunaEvents: tunaEvents
            });
        }
    });
};