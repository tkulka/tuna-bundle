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
        new tuna.view.MenuTreeView({el: $('.edit-menu-tree')});
        new tuna.view.ModalError({el: $('#modalError'), tunaEvents: tunaEvents});
        new tuna.view.MenuItemEditView({el: $('form[name="menu"]'), tunaEvents: tunaEvents});

        tuna.file.init(tunaEvents);

        //WYSIWYG EDITOR
        tuna.view.EditorView && new tuna.view.EditorView({
            selector: '.tab-pane.active .thecodeine_admin_editor',
            lang: options.lang,
            events: tunaEvents
        });

        tunaEvents.on('editorLoaded', function(element) {
            if ($(element).data('type') != 'basic') {
                var $el = $(element).siblings('.cke');
                new tuna.file.view.DropzoneView({
                    el: $el,
                    options: {
                        clickable: '.cke_button__image',
                        selector: '.cke',
                        previewTemplate: '',
                        previewsContainer: '.cke',
                        acceptedFiles: '.jpg, .jpeg, .png, .gif',
                        dropoverText: Translator.trans('Drop your images here'),
                        success: function(file, response) {
                            var $el = $(this.element).siblings('textarea');
                            var editor = CKEDITOR.instances[$el.attr('id')];
                            editor.insertHtml('<img src="' + $el.data('image-url') + response.path + '">');
                        }
                    },
                    tunaEvents: tunaEvents
                });
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
