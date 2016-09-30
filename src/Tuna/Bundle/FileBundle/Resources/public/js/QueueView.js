(function() {
    tuna.file.view.QueueView = Backbone.View.extend({
        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.fileEvents = tuna.file.events;
            this.queueCounter = 0;

            this.bindEvents();
        },

        bindEvents: function () {
            this.fileEvents.on('fileAdded', _.bind(function () {
                this.increaseQueue();
            }, this));

            this.fileEvents.on('fileCompleted', _.bind(function () {
                this.decreaseQueue();
            }, this));
        },

        increaseQueue: function () {
            if (this.queueCounter == 0) {
                this.$el.addClass('sending');
                this.tunaEvents.trigger('uploadStart');
            }
            this.queueCounter += 1;
        },

        decreaseQueue: function () {
            this.queueCounter -= 1;
            if (this.queueCounter == 0) {
                this.$el.removeClass('sending');
                this.tunaEvents.trigger('uploadEnd');
            }
        }
    })
})();