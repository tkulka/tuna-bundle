(function () {
    tuna.view.AttachmentsView = Backbone.View.extend({
        events: {
            'click [data-action="delete"]': 'onDeleteClick',
            'change input[type="file"]': 'onInputFileChange',
            'click .close': 'onClose',
            'close': 'onClose',
            'open': 'onOpen',
            'click': 'onClick',
            'click .a2lix_translationsLocales li a': 'onLanguageChange'
        },

        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.$el.addClass('magictime');
            this._initSortable();
            this.$wrapper = this.$('.tuna_cms_admin_attachments');
            this.$wrapper.data('index', this.$('li.item').length);

            var dropzoneOptions = this.$('[data-dropzone-options]').data('dropzone-options');
            if (dropzoneOptions) {
                new tuna.file.view.DropzoneView({
                    el: $(dropzoneOptions.selector),
                    options: dropzoneOptions,
                    parentView: this,
                    tunaEvents: this.tunaEvents
                });
            }
        },

        onClose: function () {
            this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
        },

        onOpen: function () {
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

        onClick: function (e) {
            e.stopPropagation();
        },

        addItem: function (file) {
            this._destroySortable();
            var prototype = this.$('.tuna_cms_admin_attachments a').data('prototype');
            var index = this.$wrapper.data('index') + 1;
            this.$wrapper.data('index', index);

            var $newForm = $(prototype.replace(/__name__/g, index));

            $newForm.find('.input--path').val(file.path);
            $newForm.find('.input--filename, input[type="text"]').val(file.originalName);

            var options = this.$('.tuna_cms_admin_attachments').data('dropzone-options');

            $newForm.find('.options-container .preview').append(options.previewTemplate.replace('__path__', file.path));
            this.$('.attachments').append($newForm);

            this._initSortable();
            this.recalculateItemPositions();
        },

        onDeleteClick: function (e) {
            $(e.currentTarget).closest('.item').remove()
        },

        onLanguageChange: function (e) {
            Backbone.trigger('LanguageChange', e);
        },

        onInputFileChange: function (e) {
            var fileName = e.target.value.split(/(\\|\/)/g).pop();
            var container = $(e.target.closest('.item')).find('.item-name .tab-content');
            container.find('.attachment-name').remove();
            container.append('<p class="attachment-name">' + Translator.trans('Added') + ': ' + fileName + '</p>')
        },
        uploadCallback: function (response) {
            this.addItem(response);
        }
    });
})();
