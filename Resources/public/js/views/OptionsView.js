(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': 'onGalleryOpen',
            'click .btn-attachments': 'onAttachmentsOpen'
        },

        initialize: function (options) {
            this.$el.addClass('magictime');
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
