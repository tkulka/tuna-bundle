window.tuna = window.tuna || {};
window.tuna.view = window.tuna.view || {};

/**
 * Wysiwyg editor
 *
 * @type {*|void}
 */
tuna.view.EditorView = Backbone.View.extend({

    summernoteOptions: {
        dialogsInBody: true,
        styleTags: ['h2', 'h3', 'h4', 'h5', 'p', 'blockquote'],
        toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'hr', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['options', ['fullscreen']]
        ],
        onPaste: function(e) {
            e.preventDefault();
            var html = (e.originalEvent || e).clipboardData.getData('text/html') || (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertHTML', false, $.htmlClean(html, {
                format: false,
                replace: [['h1'],'h2'],
                removeAttrs: ['class', 'style', 'font'],
                allowedAttributes: ['width', 'height', 'src', 'frameborder', 'allowfullscreen'],
                allowedTags: ['p', 'i', 'b', 'u', 'strong', 'iframe', 'ul', 'li'],
                removeTags: ['span', 'basefont', 'center', 'dir', 'font', 'frame', 'frameset', 'isindex', 'menu', 'noframes', 's', 'strike','br', 'canvas', 'hr', 'img'],
                allowEmpty: ['iframe'],
                tagAllowEmpty: ['iframe'],
                allowComments: false,
            }));
        }
    },
    summernote: null,

    initialize: function(options) {
        this.options = options;
        this.summernote = $(this.options.selector).eq(0).summernote(this.summernoteOptions);

        var oThis = this;
        $('.tabbable [data-toggle="tab"]').click(function(e) {
            if (!$(e.target).parent().hasClass('active')) {
                oThis.summernote.destroy();
                _.defer(function () {
                    oThis.summernote = $(oThis.options.selector).eq(0).summernote(oThis.summernoteOptions);
                });
            }
        });
    }
});
