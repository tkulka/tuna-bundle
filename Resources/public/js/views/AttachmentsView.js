(function () {
    tuna.view.AttachmentsView = Backbone.View.extend({
        events: {
            "click .add_new_attachment": "_onAddNewAttachment",
            "click .delete": "_onClickDelete",
            "change input[type='file']": "_onInputFileChange",
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

        recalculateItemPositions: function () {
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
                    oThis.recalculateItemPositions();
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
            var $wrapper = $(e.currentTarget);
            var prototype = $wrapper.data('prototype');

            if ($wrapper.data('index')) {
                var index = $wrapper.data('index') + 1;
            } else if (this.$('li.item').length == 0) {
                var index = 1;
            } else {
                var index = _.max(_.map(this.$('li.item'), function (item) {
                        return parseInt($(item).data('item-number'));
                    })) + 1;
            }

            $wrapper.data('index', index);

            var newForm = prototype.replace(/__name__/g, index);

            this.$('.attachments').append($(newForm));
            this._initSortable();
            this.recalculateItemPositions();
        },

        _onClickDelete: function (e) {
            $(e.currentTarget).closest('.item').remove()
        },

        _onLanguageChange: function (e) {
            Backbone.trigger('LanguageChange', e);
        },

        _onInputFileChange: function (e) {
            var fileName = e.target.value.split(/(\\|\/)/g).pop();
            var container = $(e.target.closest('.item')).find('.item-name .tab-content');
            container.find('.attachment-name').remove();
            container.append('<p class="attachment-name">Dodano: ' + fileName + '</p>')
        }
    });
})();
