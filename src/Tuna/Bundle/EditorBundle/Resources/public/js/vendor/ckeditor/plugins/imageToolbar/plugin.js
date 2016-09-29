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

        this.$selectedImage = '';
        this.$container = $(editor.container.$);
        this.$contents = this.$container.find('.cke_contents');
        this.$imageToolbar = $('<div class="image-toolbar">' +
                '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="25">25%</div>' +
                '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="50">50%</div>' +
                '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="75">75%</div>' +
                '<div class="image-toolbar__button image-toolbar__button--resize" data-resize="100">100%</div>' +
                '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--left" data-float="left"></div>' +
                '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--center" data-center="center"></div>' +
                '<div class="image-toolbar__button image-toolbar__button--float image-toolbar__button--right" data-float="right"></div>' +
            '</div>');

        this.$container.append(this.$imageToolbar);

        this.bindEvents();
    },

    bindEvents: function() {
        var self = this;

        this.$contents.on('click', function(e) {
            var $target = $(e.target);

            if ($target.prop('tagName') == 'IMG') {
                self.$selectedImage = $target;
                self.showToolbar($target);
            } else {
                self.hideToolbar();
            }
        });

        this.$imageToolbar.find('[data-resize]').on('click', function(e) {
            self.resizeImg(e);
        });

        this.$imageToolbar.find('[data-float]').on('click', function(e) {
            self.floatImg(e);
        });

        this.$imageToolbar.find('[data-center]').on('click', function(e) {
            self.centerImg(e);
        });
    },

    showToolbar: function($target) {
        var offset = this.$container.find('.cke_top').outerHeight() + parseInt(this.$contents.css('margin-top').replace('px', ''));

        this.$imageToolbar.show().css({
            top: $target.position().top + offset,
            left: $target.position().left
        });
    },

    hideToolbar: function() {
        this.$imageToolbar.hide();
    },

    resizeImg: function(e) {
        var value = $(e.target).data('resize');
        this.$selectedImage.css('width', value + '%');
    },

    floatImg: function(e) {
        var value = $(e.target).data('float');
        this.$selectedImage.css('float', value).removeClass('centered');
    },

    centerImg: function() {
        this.$selectedImage.css('float', 'none').addClass('centered');
    }
});