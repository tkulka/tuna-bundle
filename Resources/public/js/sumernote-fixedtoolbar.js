(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(window.jQuery);
    }
}(function ($) {
    $.extend($.summernote.plugins, {
        'fixedToolbar': function (context) {

            var $editor = context.layoutInfo.editor,
                $toolbar = context.layoutInfo.toolbar;

            var repositionToolbar = function() {
                var windowTop = $(window).scrollTop(),
                    editorTop = $editor.offset().top,
                    editorBottom = editorTop + $editor.height();

                if (windowTop > editorTop && windowTop < editorBottom) {
                    $toolbar.css({
                        'position': 'fixed',
                        'top': '50px',
                        'width': $editor.width() + 'px',
                        'z-index': '99999'
                    });
                    $editor.css('padding-top', '42px');
                } else {
                    $toolbar.css('position', 'static');
                    $editor.css('padding-top', '0px');
                }
            };

            this.initialize = function () {
                $('.admin-container .inside').scroll(repositionToolbar);
                repositionToolbar();
            };
        }
    });
}));
