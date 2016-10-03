(function() {
    tuna.file.view.QueueView = Backbone.View.extend({
        template: _.template('<div class="upload-text">' + Translator.trans('Your files are uploading. Please wait...') + '</div>'),

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
            if (this.queueCounter == 0) {
                this.startQueue();
            }
            this.queueCounter += 1;
        },

        decreaseQueue: function () {
            this.queueCounter -= 1;
            if (this.queueCounter == 0) {
                this.endQueue();
            }
        },

        startQueue: function () {
            this.tunaEvents.trigger('file.uploadStart');
            this.render();
        },

        endQueue: function () {
            this.tunaEvents.trigger('file.uploadEnd');
            this.unrender();
        },

        render: function () {
            this.$el.html(this.template);
        },

        unrender: function() {
            this.$el.html('');
        }
    })
})();