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
            this.events = options.events || _.extend({}, Backbone.Events);
            this.queueCounter = 0;

            this.bindEvents();
        },

        bindEvents: function () {
            this.events.on('file.fileAdded', this.increaseQueue, this);
            this.events.on('file.fileCompleted', this.decreaseQueue, this);
        },

        increaseQueue: function () {
            this.queueCounter++;
            this.render();

            if (this.queueCounter == 1) {
                this.events.trigger('file.uploadStart');
            }
        },

        decreaseQueue: function () {
            this.queueCounter--;
            this.render();

            if (this.queueCounter == 0) {
                this.events.trigger('file.uploadEnd');
            }
        },

        render: function () {
            this.$el.html(this.template({filesCount: this.queueCounter}));
        }
    })
})();
