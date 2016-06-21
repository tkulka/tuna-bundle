tuna.view.AttachmentsView = Backbone.View.extend({
    events: {
        "click .add_new_attachment": "_onAddNewAttachment",
        "click .delete": "_onClickDelete",
        'click .close': "_onClose",
        'close': "_onClose",
        'open': "_onOpen",
        'click': "_onClick",
        'click .a2lix_translationsLocales li a': "_onLanguageChange"
    },

    initialize: function () {
        this.$el.addClass('magictime');
        this._initSortable();
    },

    _onClose: function () {
        this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
    },

    _onOpen: function () {
        $('.admin-gallery-container').trigger('close');
        this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');
    },

    recalculateImagePositions: function () {
        this.$('input.position').each(function (idx) {
            $(this).val(idx);
        });
    },

    _initSortable: function () {
        var oThis = this;
        this.$('.attachments')
            .sortable({
                handle: '.handle'
            })
            .disableSelection()
            .bind('sortupdate', function () {
                oThis.recalculateImagePositions();
            });
    },

    _destroySortable: function () {
        this.$('.attachments').sortable('destroy');
    },

    _onClick: function (e) {
        e.stopPropagation();
    },

    _onAddNewAttachment: function (e) {
        this._destroySortable();

        var prototype = $(e.currentTarget).data('prototype');
        // get the new index
        var index = $(e.currentTarget).data('index');
        index = index ? index : $('li.item').size();
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $(e.currentTarget).data('index', index + 1);

        this.$('.attachments').prepend($(newForm).addClass('jelly-in'));
        this.recalculateImagePositions();
        this._initSortable();
    },

    _onClickDelete: function (e) {
        $(e.currentTarget).parent().remove()
    },

    _onLanguageChange: function (e) {
        Backbone.trigger('LanguageChange', e);
    }
});
