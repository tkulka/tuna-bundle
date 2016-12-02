(function () {
    tuna.file.view.QueueView = Backbone.View.extend({
        template: _.template(
            '<% if (filesCount > 0) { %>' +
            '<div class="upload-text">' +
            "<%= Translator.trans('file.uploading.info', { 'count': filesCount }) %>" +
            '</div>' +
            '<% } %>'
        ),

        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.queueCounter = 0;

            this.bindEvents();
        },

        bindEvents: function () {
            this.tunaEvents.on('file.fileAdded', this.increaseQueue, this);
            this.tunaEvents.on('file.fileCompleted', this.decreaseQueue, this);
        },

        increaseQueue: function () {
            this.queueCounter++;
            this.render();

            if (this.queueCounter == 1) {
                this.tunaEvents.trigger('file.uploadStart');
            }
        },

        decreaseQueue: function () {
            this.queueCounter--;
            this.render();

            if (this.queueCounter == 0) {
                this.tunaEvents.trigger('file.uploadEnd');
            }
        },

        render: function () {
            this.$el.html(this.template({filesCount: this.queueCounter}));
        }
    })
})();
