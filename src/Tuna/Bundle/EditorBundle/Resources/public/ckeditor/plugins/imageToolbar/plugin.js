CKEDITOR.plugins.add('imageToolbar', {
    init: function(editor) {
        var self = this;

        editor.on('instanceReady', function() {
            self.setup(this);
        });
    },

    setup: function(editor) {
        var styles = '<link type="text/css" data-id="image-toolbar-style" rel="stylesheet" href="' + this.path + 'image-toolbar.css" />';

        if ($('[data-id="image-toolbar-style"]').length < 1) {
            $(styles).insertBefore($('head link').first());
        }

        editor.$selectedImage = '';
        editor.$container = $(editor.container.$);
        editor.$contents = editor.$container.find('.cke_contents');
        editor.$imageToolbar = $('<div class="image-toolbar">' +
            '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="25">25%</div>' +
            '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="50">50%</div>' +
            '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="75">75%</div>' +
            '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="100">100%</div>' +
            '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--left" data-float="left"></div>' +
            '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--center" data-center="center"></div>' +
            '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--right" data-float="right"></div>' +
            '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--delete" data-delete="delete"></div>' +
            '</div>');

        editor.$container.append(editor.$imageToolbar);

        this.bindEvents(editor);
    },

    bindEvents: function(editor) {
        var self = this;

        editor.$contents.on('click', function(e) {
            var $target = $(e.target);

            if ($target.prop('tagName') == 'IMG') {
                editor.$selectedImage = $target;
                self.showToolbar($target, editor);
            } else {
                self.hideToolbar(editor);
            }
        });

        editor.$imageToolbar.find('[data-resize]').on('click', function(e) {
            self.resizeImg(e, editor);
        });

        editor.$imageToolbar.find('[data-float]').on('click', function(e) {
            self.floatImg(e, editor);
        });

        editor.$imageToolbar.find('[data-center]').on('click', function() {
            self.centerImg(editor);
        });

        editor.$imageToolbar.find('[data-delete]').on('click', function() {
            self.deleteImg(editor);
        })
    },

    showToolbar: function($target, editor) {
        var offsetTop = editor.$container.find('.cke_top').outerHeight() + parseInt(editor.$contents.css('margin-top').replace('px', ''));
        var offsetLeft =  parseInt($target.css('margin-left').replace('px', ''));

        editor.$imageToolbar.show().css({
            top: $target.position().top + offsetTop,
            left: $target.position().left + offsetLeft
        });
    },

    hideToolbar: function(editor) {
        editor.$imageToolbar.hide();
    },

    resizeImg: function(e, editor) {
        var value = $(e.target).data('resize');
        editor.$selectedImage.css('width', value + '%');
    },

    floatImg: function(e, editor) {
        var value = $(e.target).data('float');
        editor.$selectedImage.css('float', value).removeClass('centered');
    },

    centerImg: function(editor) {
        editor.$selectedImage.css('float', 'none').addClass('centered');
    },

    deleteImg: function(editor) {
        editor.$selectedImage.remove();
        this.hideToolbar(editor);
    }
});
