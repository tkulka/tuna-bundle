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
        this.events = _.extend({}, Backbone.Events);

        this.initBaseViews();
        this.beforeFileBundle();
        tuna.file.initFileBundle(this.events);
        this.initEditor();
        this.initInputPlugins();
    },

    beforeFileBundle: function () {
    },

    initBaseViews: function () {
        new tuna.view.NavigationView({el: $('nav')});
        new tuna.view.ListView({el: $('.admin-list')});
        new tuna.view.OptionsView({el: $('.admin-option-container'), tunaEvents: this.events});
        new tuna.view.GalleryView({el: $('.admin-gallery-container'), tunaEvents: this.events});
        new tuna.view.AttachmentsView({el: $('.admin-attachments-container'), tunaEvents: this.events});
        new tuna.view.EditView({el: $('.admin-container'), lang: this.options.lang});
        new tuna.view.AddableEntitySelectView({el: $('.addable-entity-select')});
        new tuna.view.SortableView({el: $('[data-sortable-url]')});
        new tuna.view.MenuTreeView({el: $('.edit-menu-tree')});
        new tuna.view.ModalError({el: $('#modalError'), tunaEvents: this.events});
        new tuna.view.MenuItemEditView({el: $('form[name="menu"]'), tunaEvents: this.events});
    },

    initEditor: function () {
        tuna.view.EditorView && new tuna.view.EditorView({
            selector: '.tab-pane.active .thecodeine_admin_editor',
            lang: this.options.lang,
            events: this.events
        });

        this.events.on('editorLoaded', _.bind(function (element) {
            var $element = $(element);
            var $el = $element.siblings('.cke');

            if ($element.data('type') == 'basic') return;

            $el.append('<div class="hidden-dropzone-button" style="display:none;"></div>');
            new tuna.file.view.DropzoneView({
                el: $el,
                options: {
                    clickable: '.hidden-dropzone-button',
                    selector: '.cke',
                    previewTemplate: '',
                    previewsContainer: '.cke',
                    acceptedFiles: '.jpg, .jpeg, .png, .gif',
                    dropoverText: Translator.trans('Drop your images here'),
                    success: function (file, response) {
                        var $el = $(this.element).siblings('textarea');
                        var editor = CKEDITOR.instances[$el.attr('id')];
                        editor.insertHtml('<img src="' + $el.data('image-url') + response.path + '">');
                    }
                },
                tunaEvents: this.events
            });
        }, this));
    },

    initInputPlugins: function () {
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
