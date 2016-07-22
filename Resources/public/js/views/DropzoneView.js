(function () {
    tuna.view.DropzoneView = Backbone.View.extend({
        
        initialize: function (object) {
            Dropzone.autoDiscover = false;

            this.options = object.options;
            this.setupOptions();
            this.createDropzone();
            this.bindEvents();
        },

        createDropzone: function () {
            this.dropzone = this.$el.dropzone(this.defaultOttions)
        },

        bindEvents: function () {
            this.dropzone.on('dragbetterenter', _.bind(function() {
                this.$el.addClass('drag-over');
            }, this));

            this.dropzone.on('dragbetterleave', _.bind(function() {
                this.$el.removeClass('drag-over');
            }, this));

            this.dropzone.on("queuecomplete", _.bind(function () {
                this.onSendingComplate();
                this.removeAllFiles();
            }, this));

            this.dropzone.on("sending", _.bind(function () {
                this.onSending();
            }, this));
        },
        
        setupOptions: function () {
            this.defaultOttions = _.extend({
                url: '/admin/file/upload/',
                acceptedFiles: '.jpg, .jpeg, .gif',
                paramName: 'file',
                clickable:'[data-dropzone-clickable]',
                addedfile: function () {}
            }, this.options);

            this.$el.attr('data-dropover-text', this.options.dropoverText);
        },

        onSendingComplate: function () {
        },

        onSending: function () {
        }
    });
})();
