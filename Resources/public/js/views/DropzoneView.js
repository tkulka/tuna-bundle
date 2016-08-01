(function () {
    tuna.view.DropzoneView = Backbone.View.extend({

        initialize: function (options) {
            Dropzone.autoDiscover = false;

            this.$errorModal = $('#modalError');
            this.$errorModalBody = this.$errorModal.find('.modal-body');

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
                url: '/admin/file/upload/',
                acceptedFiles: '.jpg, .jpeg, .gif',
                paramName: 'file',
                dictInvalidFileType: Translator.trans('You can\'t upload files of this type.'),
                clickable: '[data-dropzone-clickable]',
                addedfile: function () {},
                error: function (file, error, xhr) {

                    if (xhr) error = error.messages;

                    if (!dropzoneView.$errorModal.is(':visible')) {
                        dropzoneView.$errorModal.modal({
                            keyboard: true
                        });
                    }

                    dropzoneView.$errorModalBody.append('<p><strong>'+ file.name +'</strong> - '+ error +'</p>');
                    dropzoneView.$errorModal.on('hide.bs.modal', function (event) {
                        dropzoneView.$errorModalBody.html('')
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
                        tuna.events.trigger('backgroundJobEnd');
                        this.removeAllFiles();
                    });

                    this.on('sending', function () {
                        tuna.events.trigger('backgroundJobStart');
                    });
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
