window.tuna || (window.tuna = {
    locale: 'pl',
    website: {},
    backbone: {},
    templates: {},
    view: {
        helpers: {}
    },
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

        //init main views
        new tuna.view.NavigationView({el: $('nav')[0]});
        new tuna.view.ListView({el: $('.admin-list')[0]});
        new tuna.view.OptionsView({el: $('.admin-option-container')[0]});
        new tuna.view.GalleryView({el: $('.admin-gallery-container')[0]});
        new tuna.view.AttachmentsView({el: $('.admin-attachments-container')[0]});
        new tuna.view.EditView({el: $('.admin-container')[0], lang: options.lang});
        new tuna.view.AddableEntitySelectView({el: $('.addable-entity-select')});

        bindPlugins();

        //WYSIWYG EDITOR
        tuna.view.EditorView && new tuna.view.EditorView({
            selector: '.tab-pane.active .thecodeine_admin_editor',
            lang: options.lang
        });
    },

    goToUri: function (uri) {
        top.location.href = uri;
    }
};

function tunaConfirm(msg) {
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
}

function tunaAlert(msg) {
    alert(msg);
}

function bindSortable() {
    function lockWidths($table) {
        $table.find('td, th').each(function (i, item) {
            $(item).width($(item).width());
        });
    }

    function unlockWidths($table) {
        $table.find('td, th').each(function (i, item) {
            $(item).width('');
        });
    }

    $('[data-sortable-url]').each(function (i, sortableWrapper) {
        var $sortableWrapper = $(sortableWrapper);
        $sortableWrapper.find('tbody').sortable({
            handle: '.handle',
            stop: function (event, ui) {
                unlockWidths($(ui.item).closest('table'));
            },
            change: function (event, ui) {
                $(ui.item).closest('[data-sortable-url]').find('[data-action="save-order"]').fadeIn();
            }
        }).find('.handle').on('mousedown', function () {
            lockWidths($(this).closest('table'));
        });

        $sortableWrapper.find('[data-action="save-order"]').on('click', function (event) {
            $.ajax({
                type: 'POST',
                data: {
                    order: $sortableWrapper.find('tbody').sortable('toArray', {attribute: 'data-id'})
                },
                url: $sortableWrapper.data('sortable-url'),
                success: function (data) {
                    $(event.currentTarget).fadeOut();
                }
            })
        });
    });
}

function bindPlugins() {
    bindSortable();
}
