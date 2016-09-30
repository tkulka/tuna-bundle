(function () {
    tuna.file.view.DropzoneView = Backbone.View.extend({

        initialize: function (options) {
            Dropzone.autoDiscover = false;

            this.tunaEvents = options.tunaEvents;
            this.fileEvents = tuna.file.events;
            this.parentView = options.parentView;
            this.options = options.options;

            this.setupOptions();
            this.createDropzone();
            this.bindEvents();
        },

        createDropzone: function () {
            this.dropzone = this.$el.dropzone(this.defaultOptions);
        },

        bindEvents: function () {
            this.dropzone.on('dragbetterenter', _.bind(function () {
                this.$el.addClass('drag-over');
            }, this));

            this.dropzone.on('dragbetterleave', _.bind(function () {
                this.$el.removeClass('drag-over');
            }, this));
        },

        setupOptions: function () {
            var dropzoneView = this;

            this.defaultOptions = _.extend({
                url: Routing.generate('tuna_file_upload'),
                acceptedFiles: '.jpg, .jpeg, .gif',
                paramName: 'file',
                dictInvalidFileType: Translator.trans('You can\'t upload files of this type.'),
                dictMaxFilesExceeded: Translator.trans('You can\'t upload any more files.'),
                clickable: '[data-dropzone-clickable]',
                addedfile: function () {},
                error: function (file, error, xhr) {
                    if (xhr) error = error.messages;
                    dropzoneView.tunaEvents.trigger('errorOccurred', {
                        title: Translator.trans('File upload error'),
                        message: file.name + ' - ' + error
                    });
                },
                init: function () {
                    this.on('success', function (file, response) {
                        if (dropzoneView.parentView) {
                            dropzoneView.parentView.uploadCallback(response);
                        } else {
                            dropzoneView.uploadCallback(response);
                        }
                    });

                    this.on('queuecomplete', function () {
                        this.removeAllFiles();
                    });

                    this.on('addedfile', function () {
                        dropzoneView.fileEvents.trigger('fileAdded');
                    });

                    this.on('complete', function() {
                        dropzoneView.fileEvents.trigger('fileCompleted');
                    })
                }
            }, this.options);

            this.$el.attr('data-dropover-text', this.options.dropoverText);
        },

        uploadCallback: function (response) {
            this.$('.input--path').val(response.path);
            this.$('.input--filename').val(response.originalName);
            this.$('.preview').html(
                this.options.previewTemplate.replace('__path__', response.path)
            );
        }
    });
})();
