(function () {
    tuna.view.DropzoneView = Backbone.View.extend({

        initialize: function (options) {
            Dropzone.autoDiscover = false;

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
                clickable: '[data-dropzone-clickable]',
                addedfile: function () {},
                error: function (file, response) {
                    alert(response);
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
                this.options.previewTemplate.replace("__path__", response.path)
            );
        }
    });
})();
