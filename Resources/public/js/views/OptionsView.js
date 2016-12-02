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
            this.listenTo(this.tunaEvents, {
                'file.uploadStart': this.onFileUploadStart,
                'file.uploadEnd': this.onFileUploadEnd
            });
        },

        onFileUploadStart: function () {
            $('body').addClass('sending');
            this.disableSubmit(true);
        },

        onFileUploadEnd: function () {
            $('body').removeClass('sending');
            this.disableSubmit(false);
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
