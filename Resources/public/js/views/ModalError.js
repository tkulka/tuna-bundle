(function () {
    tuna.view.ModalError = Backbone.View.extend({
        el: '#modalError',
        initialize: function (options) {
            this.options = options;
            this.bindEvents();
            this.appendMessages();
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

        appendMessages: function () {
            this.$el.find('.modal-title').html(this.options.title);
            this.$el.find('.modal-body').append('<p>' + this.options.message + '</p>');
        }
    });
})();
