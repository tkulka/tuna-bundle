window.tuna = window.tuna || {};
window.tuna.view = window.tuna.view || {};

/**
 * Wysiwyg editor
 *
 * @type {*|void}
 */
tuna.view.EditorView = Backbone.View.extend({

    types: {
        basic: {
            toolbar: 'basic'
        }
    },

    initialize: function (options) {
        this.options = options;
        this.events = options.events || _.extend({}, Backbone.Events);

        $('.nav-tabs [data-toggle="tab"]')
            .click(_.bind(this.loadEditors, this))
            .filter(':first').trigger('click');

        this.events.on('editor.loadEditors', _.bind(this.loadEditors, this));
    },

    loadEditors: function () {
        _.defer(_.bind(function () {
                this.initEditor($('.tabbable').find('.tab-pane.active' + this.options.selector), tuna.config.localeMap[this.options.lang]);
            }, this)
        );
    },

    initEditor: function ($element, language) {
        var editorLang = language.split('_');

        CKEDITOR.config.language = editorLang[0];
        CKEDITOR.config.customConfig = '/bundles/thecodeineeditor/js/editorConfig.js';

        $element.each(_.bind(function (index, item) {
            var $item = $(item);
            var config = $item.data('type') == 'basic' ? this.types.basic : {};
            var editor = CKEDITOR.instances[$item.attr('id')];

            if (!editor) {
                CKEDITOR.replace($item.attr('id'), config);
            }
        }, this));

        if (CKEDITOR.isInstanceReadyBound) return;

        CKEDITOR.isInstanceReadyBound = true;
        CKEDITOR.on('instanceReady', _.bind(function (e) {
            var editor = e.editor;
            var element = editor.element.$;
            this.events.trigger('editorLoaded', element);

            if ($(editor.container.$).attr('data-dropover-text')) {
                var imageOld = editor.commands.image.exec;

                var imageCmd = new CKEDITOR.command(editor, {
                    exec: function (e) {
                        var el = editor.getSelection().getSelectedElement();

                        if (el && $(el.$).prop('tagName') == 'IMG') {
                            imageOld.apply(this, arguments);
                        } else {
                            $('.hidden-dropzone-button').click();
                        }
                    }
                });

                editor.commands.image.exec = imageCmd.exec;
            }

            editor.on('mode', function () {
                if (this.mode == 'source') {
                    var $textarea = $(editor.container.$).find('.cke_source');
                    $textarea.height($textarea[0].scrollHeight);
                }
            })

        }, this));
    }
});
