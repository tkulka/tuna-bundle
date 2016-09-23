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
        basic: {
            toolbar: 'basic'
        }
    },

    summernote: null,

    initialize: function (options) {
        this.options = options;
        var oThis = this;

        $('.nav-tabs [data-toggle="tab"]').click(function (e) {
            var $tabbable = $('.tabbable');

            _.defer(function () {
                oThis.initEditor($tabbable.find('.tab-pane.active' + oThis.options.selector), tuna.config.localeMap[options.lang]);
            });
        })
            .filter(':first').trigger('click');
    },

    initEditor: function ($element, language) {
        var editorLang = language.split('_');

        CKEDITOR.config.language = editorLang[0];
        CKEDITOR.config.customConfig = '/bundles/thecodeineadmin/js/editorConfig.js';

        $element.each(_.bind(function (index, item) {
            var $item = $(item);
            var config = $item.data('type') == 'basic' ? this.types.basic : {};
            var editor = CKEDITOR.instances[$item.attr('id')];

            if (!editor) {
                CKEDITOR.replace($item.attr('id'), config);
            }
        }, this));

        CKEDITOR.on('instanceReady', _.bind(function(e) {
            var editor = e.editor;
            var element = editor.element.$;
            this.options.tunaEvents.trigger('editorLoaded', element);

            if ($(editor.container.$).attr('data-dropover-text')) {
                var imageOld = editor.commands.image.exec;

                var imageCmd = new CKEDITOR.command(editor, {
                    exec: function(e) {
                        var $el = $(editor.getSelection().getNative().anchorNode.firstElementChild);

                        if ($el.prop('tagName') == 'IMG') {
                            imageOld.apply(this, arguments);
                        }
                    }
                });

                editor.commands.image.exec = imageCmd.exec;
            }

            editor.on('mode', function() {
                if (this.mode == 'source') {
                    var $textarea = $(editor.container.$).find('.cke_source');
                    $textarea.height($textarea[0].scrollHeight);
                }
            })

        }, this));


    }
});
