(function () {
    tuna.view.GalleryView = Backbone.View.extend({
        events: {
            'click [data-type="video"]': 'onAddItemClick',
            'click [data-action="delete"]': 'onDeleteClick',
            'keyup input[type="url"]': 'onVideoUrlInputChange',
            'click .close': 'onClose',
            'close': 'onClose',
            'open': 'onOpen',
            'click': 'onClick',
            'click .a2lix_translationsLocales li a': 'onLanguageChange',
            'showError': 'onShowError'
        },
        initialize: function () {
            this.$el.addClass('magictime');
            this.initSortable();
            this.$wrapper = this.$('.thecodeine_admin_gallery');
            this.$wrapper.data('index', this.$('li.item').length);

            var options = this.$('[data-dropzone-options]').data('dropzone-options');
            if (options) {
                new tuna.view.DropzoneView({
                    el: $(options.selector),
                    options: options,
                    parentView: this
                });
            }
        },
        onClick: function (e) {
            e.stopPropagation();
        },
        initSortable: function () {
            var oThis = this;
            this.$('.gallery-items')
                .sortable({
                    handle: '.handle'
                })
                .disableSelection()
                .bind('sortupdate', function () {
                    oThis.recalculateItemPositions();
                });
        },
        onClose: function () {
            this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
        },
        onOpen: function () {
            $('.admin-attachments-container').trigger('close');
            this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');
        },
        recalculateItemPositions: function () {
            this.$('input.position').each(function (idx) {
                $(this).val(idx);
            });
        },
        loadItemForm: function (selector, image) {
            var $form = this.$el.closest('form');

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                success: function (html) {
                    $(selector).replaceWith(
                        $(html).find(selector)
                    );
                    $(selector).addClass('loaded');

                    var options = $(selector).find('.thecodeine_admin_main_image').data('dropzone-options');

                    $(selector).find('.preview').html(options.previewTemplate.replace('__path__', image.path));
                    $(selector).find('.input--path').val(image.path);
                    $(selector).find('.input--filename ').val(image.originalName);
                }
            });
        },
        addItem: function (type, image) {
            var itemsId = this.$wrapper.data('itemsId');
            var prototype = this.$wrapper.data('prototype');
            var index = this.$wrapper.data('index');
            this.$wrapper.data('index', index + 1);

            var $newForm = $(prototype.replace(/__name__/g, index));
            $newForm.find('input[type="hidden"]').val(type);

            this.$('.gallery-items').append($newForm);
            this.loadItemForm('#' + itemsId + "_" + index, image);
            tuna.website.enableFancySelect(this.$('select'));
            this.recalculateItemPositions();
        },
        onAddItemClick: function (event) {
            event.preventDefault();
            this.addItem($(event.currentTarget).data('type'));
        },
        onDeleteClick: function (e) {
            $(e.currentTarget).closest('.item').remove();
        },
        onVideoUrlInputChange: function (event) {
            var $el = $(event.target);
            var url = event.target.value;
            var id = $(event.currentTarget).closest('.item').attr('id');

            if (/(youtu\.be|youtube\.com|vimeo\.com)/.test(url)) {
                this.loadItemForm('#' + id + ' .video-player');
                $el.removeClass('error').siblings('.form-error').remove();
            } else {
                $el.trigger('showError', Translator.trans('Paste url to YouTube or Vimeo.'));
            }
        },
        onShowError: function (e, message) {
            var $el = $(e.target);
            var error = '<span class="form-error">' + message + '</span>';
            $el.siblings('.form-error').remove();
            $el.addClass('error').after(error);
        },
        onLanguageChange: function (e) {
            Backbone.trigger('LanguageChange', e);
        },
        uploadCallback: function (response) {
            this.addItem('image', response);
        }
    });
})();
