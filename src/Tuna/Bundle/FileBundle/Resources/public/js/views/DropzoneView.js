var tuna = tuna || {};

(function () {
    tuna.file = tuna.file || {};
    tuna.file.view = tuna.file.view || {};

    tuna.file.view.DropzoneView = Backbone.View.extend({
        initialize: function (options) {
            Dropzone.autoDiscover = false;

            this.tunaEvents = options.tunaEvents || _.extend({}, Backbone.Events);
            this.parentView = options.parentView;
            this.options = options.options;

            this.setupOptions();
            this.elaborateClickableSelector();
            this.createDropzone();
            this.bindEvents();
        },

        createDropzone: function () {
            this.dropzone = this.$el.dropzone(this.options);
        },

        bindEvents: function () {
            this.dropzone.on('dragbetterenter', _.bind(function () {
                this.$el.addClass('drag-over');
            }, this));

            this.dropzone.on('dragbetterleave', _.bind(function () {
                this.$el.removeClass('drag-over');
            }, this));
        },

        elaborateClickableSelector: function () {
            if (this.options.clickableExternal) return;

            this.$el.addClass(this.cid);
            this.options.clickable = '.' + this.cid + ' ' + this.options.clickable;
        },

        getUploaderUrl: function () {
            return Routing.generate('tuna_file_upload');
        },

        getText: function (text) {
            return Translator.trans(text);
        },

        setupOptions: function () {
            var dropzoneView = this;

            this.options = _.extend({
                url: this.getUploaderUrl(),
                acceptedFiles: '',
                paramName: 'file',
                dictInvalidFileType: this.getText('You can\'t upload files of this type.'),
                dictMaxFilesExceeded: this.getText('You can\'t upload any more files.'),
                isClickableExternal: false,
                clickable: '[data-dropzone-clickable]',
                previewsContainer: '.preview',
                addedfile: function () {
                },
                error: function (file, error, xhr) {
                    if (xhr) error = error.messages;
                    dropzoneView.tunaEvents.trigger('errorOccurred', {
                        title: dropzoneView.getText('File upload error'),
                        message: file.name + ' - ' + error
                    });
                },
                init: function () {
                    this.on('success', function (file, response) {
                        var view = dropzoneView.parentView ? dropzoneView.parentView : dropzoneView;
                        view.uploadCallback(response);
                    });

                    this.on('queuecomplete', function () {
                        this.removeAllFiles();
                    });

                    this.on('addedfile', function () {
                        dropzoneView.tunaEvents.trigger('file.fileAdded');
                    });

                    this.on('complete', function () {
                        dropzoneView.tunaEvents.trigger('file.fileCompleted');
                    })
                }
            }, this.options);

            this.$el.attr('data-dropover-text', this.options.dropoverText);
        },

        uploadCallback: function (response) {
            this.$('.input--path').val(response.path);
            this.$('.input--filename').val(response.originalName);
            this.$(this.options.previewsContainer).html(
                this.options.previewTemplate.replace('__path__', response.path)
            );
        }
    });
})();
