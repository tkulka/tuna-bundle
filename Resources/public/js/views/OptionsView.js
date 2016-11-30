(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': 'onGalleryOpen',
            'click .btn-attachments': 'onAttachmentsOpen'
        },

        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.$el.addClass('magictime');
            this.bindEvents();
        },

        bindEvents: function () {
            this.tunaEvents.on('file.uploadStart', function () {
                $('body').addClass('sending');
                this.disableSubmit(true);
            }.bind(this));

            this.tunaEvents.on('file.uploadEnd', function () {
                $('body').removeClass('sending');
                this.disableSubmit(false);
            }.bind(this));
        },

        disableSubmit: function (value) {
            this.$('button[type="submit"]').prop('disabled', value);
        },

        onGalleryOpen: function (event) {
            event.preventDefault();

            $('.admin-gallery-container').trigger('open');
        },

        onAttachmentsOpen: function (event) {
            event.preventDefault();

            $('.admin-attachments-container').trigger('open');
        }
    });
})();
