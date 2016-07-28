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
            this.dropzone.on('dragbetterenter', _.bind(function() {
                this.$el.addClass('drag-over');
            }, this));

            this.dropzone.on('dragbetterleave', _.bind(function() {
                this.$el.removeClass('drag-over');
            }, this));
        },
        
        setupOptions: function () {
            var dropzoneView = this;

            this.defaultOptions = _.extend({
                url: '/admin/file/upload/',
                acceptedFiles: '.jpg, .jpeg, .gif',
                paramName: 'file',
                clickable:'[data-dropzone-clickable]',
                addedfile: function () {},
                error: function (file, response) {
                    alert(response.messages);
                },
                init: function () {
                    this.on('success', function(file, response) {
                        dropzoneView.parentView.uploadCallback(response);
                    });

                    this.on("queuecomplete", function () {
                        dropzoneView.onSendingComplate();
                        this.removeAllFiles();
                    });

                    this.on("sending", function () {
                        dropzoneView.onSending();
                    });
                }
            }, this.options);

            this.$el.attr('data-dropover-text', this.options.dropoverText);
        },

        onSendingComplate: function () {
            this.$el.removeClass('sending');
        },

        onSending: function () {
            this.$el.addClass('sending');
        }
    });
})();
