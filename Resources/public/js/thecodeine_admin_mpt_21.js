tuna.view.helpers.i18nLabels = function ($labels) {
    $labels.each(function () {
        var $el = $(this);
        if ($el.data('lang')) {
            return;
        }

        var lang = $el.closest('[data-lang]').data('lang');

        if (lang) {
            $el.data('lang', lang);
            $el.text($el.text() + ' (' + lang + ')');
        }
    });
};

var originalAttachmentsView = tuna.view.AttachmentsView;

tuna.view.AttachmentsView = tuna.view.AttachmentsView.extend({
    _onOpen: function () {
        originalAttachmentsView.prototype._onOpen.apply(this, arguments);
        tuna.view.helpers.i18nLabels(this.$el.find('label'));
    },
    _onAddNewAttachment: function (e) {
        originalAttachmentsView.prototype._onAddNewAttachment.apply(this, arguments);
        tuna.view.helpers.i18nLabels(this.$el.find('label'));
    }
});

var originalGalleryView = tuna.view.GalleryView;

tuna.view.GalleryView = tuna.view.GalleryView.extend({
    events: _.extend(originalGalleryView.prototype.events, {
        'insertForm': 'onInsertForm'
    }),
    _onOpen: function () {
        originalGalleryView.prototype._onOpen.apply(this, arguments);
        tuna.view.helpers.i18nLabels(this.$el.find('label'));
    },
    onInsertForm: function (event) {
        tuna.view.helpers.i18nLabels(this.$el.find('label'));
    },
    choiceEventListener: function (index) {
        var oThis = this;

        if ($('#thecodeine_pagebundle_page_gallery_items_' + index + '_type').length > 0) {
            var id = '#thecodeine_pagebundle_page_gallery_items_';
        } else if ($('#thecodeine_newsbundle_news_gallery_items_' + index + '_type').length > 0) {
            var id = '#thecodeine_newsbundle_news_gallery_items_';
        }

        var $type = $(id + index + '_type');

        // When sport gets selected ...
        $type.change(function () {
            $type.hide();
            // ... retrieve the corresponding form.
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected sport value.
            var data = $form.serialize();
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: data,
                beforeSend: function () {
                    oThis._destroySortable();
                },
                success: function (html) {
                    // Replace current position field ...
                    $(id + index).replaceWith(
                        // ... with the returned one from the AJAX response.
                        $(html).find(id + index)
                    );
                    oThis.$el.trigger('insertForm');
                }
            });
        });
    }
});
