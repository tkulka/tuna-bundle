(function () {
    tuna.view.ModalError = Backbone.View.extend({
        el: '#modalError',
        initialize: function (options) {
            this.bindEvents();
            this.appendMessages(options.title, options.message);
            this.show();
        },

        bindEvents: function () {
            this.$el.on('hide.bs.modal', _.bind(function () {
                this.$el.find('.modal-body').html('')
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
