(function () {
    tuna.view.GalleryView = Backbone.View.extend({
        events: {
            'click [data-type="video"]': 'onAddItemClick',
            'click .delete': 'onDeleteClick',
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
            this.initDropzone();
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
        initDropzone: function () {
            var oThis = this;

            this.$el.dropzone({
                url: '/admin/news/image/upload',
                maxFilesize: 2,
                acceptedFiles: '.jpg, .jpeg',
                paramName: 'the_codeine_image_request[file]',
                clickable: 'a[data-type="image"]',
                addedfile: function () {},
                error: function (file, errorMessage) {
                    alert(errorMessage);
                },
                init: function () {
                    this.on("success", function(file, response) {
                        oThis.addItem('image', response);
                    });
                }
            }).on('dragbetterenter', function() {
                oThis.$el.addClass('drag-over');
            }).on('dragbetterleave', function() {
                oThis.$el.removeClass('drag-over');
            })
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
        loadItemForm: function (selector, response) {
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

                    $(selector).find('.image .gallery-image').css('backgroundImage', 'url('+response.path+')')
                }
            });
        },
        addItem: function (type, response) {
            var itemsId = this.$wrapper.data('itemsId');
            var prototype = this.$wrapper.data('prototype');
            var index = this.$wrapper.data('index') + 1;
            this.$wrapper.data('index', index);

            var $newForm = $(prototype.replace(/__name__/g, index));
            $newForm.find('input[type="hidden"]').val(type);

            this.$('.gallery-items').append($newForm);
            this.loadItemForm('#' + itemsId + "_" + index, response);
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
        }
    });
})();
