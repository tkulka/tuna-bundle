(function() {
    tuna.file.view.QueueView = Backbone.View.extend({
        template: _.template(
            '<% if (filesCount > 0) { %>' +
                '<div class="upload-text">' +
                    Translator.trans('file.uploading.info') +
                '</div>' +
            '<% } %>'
        ),

        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.queueCounter = 0;

            this.bindEvents();
        },

        bindEvents: function () {
            this.tunaEvents.on('file.fileAdded', _.bind(function () {
                this.increaseQueue();
            }, this));

            this.tunaEvents.on('file.fileCompleted', _.bind(function () {
                this.decreaseQueue();
            }, this));
        },

        increaseQueue: function () {
            this.queueCounter += 1;
            this.render();
        },

        decreaseQueue: function () {
            this.queueCounter -= 1;
            this.render();
        },

        render: function () {
            this.$el.html(this.template({filesCount: this.queueCounter}));
        }
    })
})();