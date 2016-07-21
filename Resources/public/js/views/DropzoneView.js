(function () {
    tuna.view.DropzoneView = Backbone.View.extend({
        
        initialize: function () {
            Dropzone.autoDiscover = false;
            this.dropzoneEl = this.$el.data('dropzone-selector') ? $(this.$el.data('dropzone-selector')) : this.$el;

            this.setupOptions();
            this.createDropzone();
            this.bindEvents();
        },

        createDropzone: function () {
            this.dropzone = this.dropzoneEl.dropzone(this.options)
        },

        bindEvents: function () {
            this.dropzone.on('dragbetterenter', _.bind(function() {
                this.dropzoneEl.addClass('drag-over');
            }, this));

            this.dropzone.on('dragbetterleave', _.bind(function() {
                this.dropzoneEl.removeClass('drag-over');
            }, this));
        },
        
        setupOptions: function () {
            this.options = _.extend({
                url: '/admin/news/image/upload',
                acceptedFiles: '.jpg, .jpeg, .gif',
                paramName: 'the_codeine_image_request[file]',
                clickable:'[data-dropzone-clickable]',
                addedfile: function () {}
            }, this.$el.data('dropzone-options'));
        }
    });
})();
