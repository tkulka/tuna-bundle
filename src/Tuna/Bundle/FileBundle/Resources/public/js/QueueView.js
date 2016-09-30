(function() {
    tuna.file.view.QueueView = Backbone.View.extend({
        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.queueCounter = 0;

            this.bindEvents();
            this.setView();
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
                this.tunaEvents.trigger('file.uploadStart');
            }
            this.queueCounter += 1;
        },

        decreaseQueue: function () {
            this.queueCounter -= 1;
            if (this.queueCounter == 0) {
                this.tunaEvents.trigger('file.uploadEnd');
            }
        },

        setView: function () {
            this.$el.append('<span>' + Translator.trans('Your files are uploading. Please wait...') + '</span>');
        }
    })
})();