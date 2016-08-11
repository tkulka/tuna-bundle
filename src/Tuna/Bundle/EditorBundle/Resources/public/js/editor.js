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
        disableDragAndDrop: true,
        callbacks: {
            onPaste: function (e) {
                e.preventDefault();
                var html = (e.originalEvent || e).clipboardData.getData('text/html') || (e.originalEvent || e).clipboardData.getData('text/plain');
                html = cleanHTML(html);
                document.execCommand('insertHTML', false, $.htmlClean(html, {
                    format: false,
                    replace: [['h1'], 'h2'],
                    removeAttrs: ['class', 'style', 'font'],
                    allowedAttributes: ['width', 'height', 'src', 'frameborder', 'allowfullscreen'],
                    allowedTags: ['h1', 'h2', 'p', 'i', 'b', 'u', 'strong', 'iframe', 'ul', 'li'],
                    removeTags: ['span', 'basefont', 'center', 'dir', 'font', 'frame', 'frameset', 'isindex', 'menu', 'noframes', 's', 'strike', 'br', 'canvas', 'hr', 'img'],
                    allowEmpty: [],
                    tagAllowEmpty: [],
                    allowComments: false
                }));
            }
        }
    },

    types: {
        default: {
            styleTags: ['h1', 'h2', 'p', 'blockquote'],
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'paragraph']],
                ['insert', ['link']],
                ['misc', ['codeview']],
                ['image-button', ['image']]
            ],
            callbacks: {
                onInit: function() {
                    new tuna.view.DropzoneView({
                        el: $(this).siblings('.note-editor'),
                        options: {
                            clickable: '.note-image-button',
                            selector: '.note-editor',
                            previewTemplate: '',
                            previewsContainer: '.note-editing-area',
                            acceptedFiles: '.jpg, .jpeg, .png, .gif',
                            dropoverText: Translator.trans('Drop your images here'),
                            success: _.bind(function(file, response) {
                                var $el = $(this);
                                $el.summernote('insertImage', $el.data('image-url') + '/' + response.path);
                            }, this)
                        },
                        tunaEvents: tuna.website.events
                    });
                }
            }
        },
        basic: {
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['misc', ['codeview']]
            ],
            callbacks: {}
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
                oThis.initEditor($tabbable.find('.tab-pane.active' + oThis.options.selector), tuna.config.localeMap[options.lang]);
            });
        })
            .filter(':first').trigger('click');
    },

    initEditor: function ($element, language) {
        $('.main_container').addClass('editor_container');

        var imageButton = function() {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="note-icon-picture"></i>',
                tooltip: Translator.trans('Picture')
            });

            return button.render();
        };

        var customButtons = {
            buttons: {
                image: imageButton
            }
        };

        _.extend(this.types.default, customButtons);

        _.each($element, function (item) {
            var $item = $(item);
            var type = $item.data('type') || 'default';
            _.extend(this.types[type].callbacks, this.summernoteOptions.callbacks);
            var options = _.extend(this.summernoteOptions, this.types[type]);
            options.lang = language;

            $item.summernote(options);
        }, this);
    }
});
