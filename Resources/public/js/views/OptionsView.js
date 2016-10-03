(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': '_onGalleryOpen',
            'click .btn-attachments': '_onAttachmentsOpen'
        },

        initialize: function (options) {

            this.tunaEvents = options.tunaEvents;
            this.$el.addClass('magictime');
            this.bindEvents();

            new tuna.view.MainImageView({
                el: this.$('.thecodeine_admin_main_image')
            });
        },

        bindEvents: function () {
            this.tunaEvents.on('file.uploadStart', _.bind(function(){
                $('body').addClass('sending');
                this.disableSubmit(true);
            }, this));

            this.tunaEvents.on('file.uploadEnd', _.bind(function(){
                $('body').removeClass('sending');
                this.disableSubmit(false);
            }, this));
        },

        disableSubmit: function (value) {
            this.$('button[type="submit"]').prop('disabled', value);
        },

        _onGalleryOpen: function () {
            $('.admin-gallery-container').trigger('open');
        },

        _onAttachmentsOpen: function () {
            $('.admin-attachments-container').trigger('open');
        }
    });
})();
