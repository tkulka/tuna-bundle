(function () {
    tuna.view.GalleryView = Backbone.View.extend({
        events: {
            'click [data-action="add-new-item"]': 'onAddItemClick',
            'click .delete': 'onDeleteClick',
            'change .image input[type="file"]': 'onFileInputChange',
            'keyup input[type="url"]': 'onVideoUrlInputChange',
            'click .close': 'onClose',
            'close': 'onClose',
            'open': 'onOpen',
            'click': 'onClick',
            'click .a2lix_translationsLocales li a': 'onLanguageChange'
        },
        initialize: function () {
            this.$el.addClass('magictime');
            this.initSortable();
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
                    oThis.recalculateImagePositions();
                });
        },
        onClose: function () {
            this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
        },
        onOpen: function () {
            $('.admin-attachments-container').trigger('close');
            this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');
        },
        recalculateImagePositions: function () {
            this.$('input.position').each(function (idx) {
                $(this).val(idx);
            });
        },
        loadItemForm: function (itemsId, index) {
            var $form = this.$el.closest('form');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                success: function (html) {
                    var selector = '#' + itemsId + "_" + index;

                    $(selector).replaceWith(
                        $(html).find(selector)
                    );
                }
            });
        },
        addItem: function (type, content) {
            var $wrapper = this.$('.thecodeine_admin_gallery');
            var itemsId = $wrapper.data('itemsId');
            var prototype = $wrapper.data('prototype');
            var index = $wrapper.data('index') | this.$('li.item').size();
            var $newForm = $(prototype.replace(/__name__/g, index));
            $newForm.find('input[type="hidden"]').val(type);

            $wrapper.data('index', index + 1);

            this.$('.gallery-items').append($newForm);
            this.loadItemForm(itemsId, index);
            tuna.website.enableFancySelect(this.$('select'));
        },
        onAddItemClick: function (event) {
            event.preventDefault();
            this.addItem($(event.currentTarget).data('type'));
        },
        onDeleteClick: function (e) {
            $(e.currentTarget).closest('.item').remove();
        },
        onFileInputChange: function (e) {
            var $element = $(e.currentTarget);
            var files = e.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('image.*')) {
                    continue;
                }
                var reader = new FileReader();

                reader.onload = (function (theFile) {
                    return function (event) {
                        var $cnt = $element.parent();
                        $cnt.css({
                            'background-position': 'center center',
                            'background-image': 'url(' + event.target.result + ')',
                            'background-size': 'cover',
                            height: '85px',
                            width: '180px',
                            position: 'relative',
                            top: 0,
                            left: 0,
                            'zIndex': 9
                        });
                    }
                })(f);

                reader.readAsDataURL(f);
            }
        },
        onVideoUrlInputChange: function (e) {
            var url = e.target.value;
            var videoId = '';

            if (/(vimeo)/.test(url)) {
                url = url.split('/');
                videoId = url.pop();
                url = 'https://player.vimeo.com/video/' + videoId;
            } else {
                if (/(youtu\.be)/.test(url)) {
                    url = url.split('/');
                } else {
                    url = url.split('=');
                }
                videoId = url.pop();
                url = 'https://www.youtube.com/embed/' + videoId;
            }

            var iframeTpl = '<iframe width="180" height="100" src="' + url + '" frameborder="0" allowfullscreen></iframe>';

            var $videoPlayer = $(e.target).closest('.item').find('.video-player');

            $videoPlayer.html(iframeTpl).css('display', 'table-cell');
        },

        onLanguageChange: function (e) {
            Backbone.trigger('LanguageChange', e);
        }
    });
})();
