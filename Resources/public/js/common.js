window.tuna || (window.tuna = {
    locale: 'pl',
    website: {},
    backbone: {},
    templates: {},
    view: {
        helpers: {}
    },
    events: {},
    model: {},
    collection: {},
    router: {},
    features: {},
    file: {
        view: {}
    },
    config: {
        localeMap: {
            'en': 'en-US',
            'pl': 'pl-PL'
        }
    }
});

/**
 * Main admin website object
 *
 */
tuna.website = {
    init: function (options) {
        this.options = options;

        var tunaEvents = _.extend({}, Backbone.Events);

        //init main views
        new tuna.view.NavigationView({el: $('nav')});
        new tuna.view.ListView({el: $('.admin-list')});
        new tuna.view.OptionsView({el: $('.admin-option-container'), tunaEvents: tunaEvents});
        new tuna.view.GalleryView({el: $('.admin-gallery-container'), tunaEvents: tunaEvents});
        new tuna.view.AttachmentsView({el: $('.admin-attachments-container'), tunaEvents: tunaEvents});
        new tuna.view.EditView({el: $('.admin-container'), lang: options.lang});
        new tuna.view.AddableEntitySelectView({el: $('.addable-entity-select')});
        new tuna.view.SortableView({el: $('[data-sortable-url]')});
        new tuna.view.ModalError({el: $('#modalError'), tunaEvents: tunaEvents});

        tuna.file.init(tunaEvents);

        //WYSIWYG EDITOR
        tuna.view.EditorView && new tuna.view.EditorView({
            selector: '.tab-pane.active .thecodeine_admin_editor',
            lang: options.lang,
            tunaEvents: tunaEvents,
            callbacks: {
                onInit: function() {
                    new tuna.file.view.DropzoneView({
                        el: $(this).siblings('.note-editor'),
                        options: {
                            clickable: '.note-image-button',
                            selector: '.note-editor',
                            previewTemplate: '',
                            previewsContainer: '.note-editing-area',
                            acceptedFiles: '.jpg, .jpeg, .png, .gif',
                            dropoverText: Translator.trans('Drop your images here'),
                            success: _.bind(function(file, response) {
                                var $el = $(this);
                                $el.summernote('insertImage', $el.data('image-url') + '/' + response.path);
                            }, this)
                        },
                        tunaEvents: tunaEvents
                    });
                }
            }
        });

        $(':checkbox').radiocheck();
        this.enableFancySelect($('select'));
        $('[data-toggle="tooltip"]').tooltip();
    },
    enableFancySelect: function ($el) {
        _.each($el, function (select) {
            var $select = $(select);
            $select.select2({
                containerCssClass: $select.attr('class'),
                dropdownCssClass: $select.attr('class')
            });
        });
    },
    confirmModal: function (msg) {
        var dfd = $.Deferred();
        $('#modalConfirm').modal('hide'); // don't allow multiple modals
        $('#modalConfirm .modal-body p').html(msg);
        $('#modalConfirm').modal({
            keyboard: true
        });
        $('#modalConfirm [data-action="accept"]').on('click', function () {
            $('#modalConfirm').off('hide.bs.modal').modal('hide');
            dfd.resolve();
        });
        $('#modalConfirm').on('hide.bs.modal', function (event) {
            dfd.reject();
        });

        return dfd.promise();
    },
    goToUri: function (uri) {
        top.location.href = uri;
    }
};
