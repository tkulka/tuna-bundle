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
        onPaste: function (e) {
            e.preventDefault();
            var html = (e.originalEvent || e).clipboardData.getData('text/html') || (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertHTML', false, $.htmlClean(html, {
                format: false,
                replace: [['h1'], 'h2'],
                removeAttrs: ['class', 'style', 'font'],
                allowedAttributes: ['width', 'height', 'src', 'frameborder', 'allowfullscreen'],
                allowedTags: ['p', 'i', 'b', 'u', 'strong', 'iframe', 'ul', 'li'],
                removeTags: ['span', 'basefont', 'center', 'dir', 'font', 'frame', 'frameset', 'isindex', 'menu', 'noframes', 's', 'strike', 'br', 'canvas', 'hr', 'img'],
                allowEmpty: ['iframe'],
                tagAllowEmpty: ['iframe'],
                allowComments: false,
            }));
        }
    },

    types: {
        default: {
            styleTags: ['h2', 'h3', 'h4', 'p'],
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['misc', ['codeview']]
            ]
        },
        basic: {
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['misc', ['codeview']]
            ]
        }
    },

    summernote: null,

    initialize: function (options) {
        this.options = options;
        var oThis = this;

        $('.nav-tabs [data-toggle="tab"]').click(function (e) {
            var $tabbable = $('.tabbable');
            $tabbable.find('.tab-pane:not(.active)' + oThis.options.selector).summernote('destroy');

            _.defer(function () {
                oThis.initEditor($tabbable.find('.tab-pane.active' + oThis.options.selector));
            });
        })
            .filter(':first').trigger('click');
    },

    initEditor: function ($element) {
        $('.main_container').addClass('editor_container');
        _.each($element, function (item) {
            var $item = $(item);
            var type = $item.data('type') || 'default';
            var options = _.extend(this.summernoteOptions, this.types[type]);

            $item.summernote(options);
        }, this);
    }
});
