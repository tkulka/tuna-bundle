(function () {
    tuna.view.ModalError = Backbone.View.extend({
        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.bindEvents();
        },

        bindEvents: function () {
            this.tunaEvents.on('errorOccurred', _.bind(function(error){
                this.appendMessages(error.title, error.message);
                this.show();
            }, this));

            this.$el.on('hide.bs.modal', _.bind(function () {
                this.$el.find('.modal-body').html('');
            }, this));
        },

        show: function () {
            this.$el.modal();
        },

        appendMessages: function (title, message) {
            this.$el.find('.modal-title').html(title);
            this.$el.find('.modal-body').append('<p>' + message + '</p>');
        }
    });
})();
