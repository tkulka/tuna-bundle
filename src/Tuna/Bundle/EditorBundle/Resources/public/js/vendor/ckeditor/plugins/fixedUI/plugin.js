CKEDITOR.plugins.add( 'fixedUI', {
    init: function( editor ) {
        var defaultConfig = {
            el: 'body',
            offset: 0
        };

        var config = CKEDITOR.tools.extend(defaultConfig, editor.config.fixedUI || {}, true);

        var $el = $(config.el);

        editor.on('instanceReady', function() {
            var $editor = $(editor.container.$);
            var $toolbar = $editor.find('.cke_top');
            var $inner = $editor.find('.cke_inner');
            var $contents = $editor.find('.cke_contents');
            var toolbarRight = $('body').width() - ($inner.offset().left + $inner.width());
            var toolbarWidth = $toolbar.width();

            $el.on('scroll', function() {
                var toolbarTop = $toolbar.offset().top;
                var $combo = $('.' + editor.id + '.cke_combopanel');

                if (toolbarTop < config.offset && $inner.offset().top + $inner.height() - 50 > config.offset + $toolbar.outerHeight()) {
                    $toolbar.css({
                        'position': 'fixed',
                        'top': config.offset,
                        'right': toolbarRight,
                        'z-index': 1,
                        'width': toolbarWidth
                    });

                    $contents.css({
                        'padding-top': $toolbar.outerHeight()
                    });

                } else if ($inner.offset().top >= config.offset || $inner.offset().top + $inner.height() - 50 <= config.offset + $toolbar.outerHeight()) {
                    $toolbar.removeAttr('style');
                    $contents.removeAttr('style');
                }

                if ($combo.length > 0) {
                    $combo.css({
                        'top': toolbarTop + $toolbar.outerHeight()
                    });
                }
            });
        });
    }
});