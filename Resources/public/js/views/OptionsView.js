(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': '_onGalleryOpen',
            'click .btn-attachments': '_onAttachmentsOpen'
        },

        initialize: function (options) {

            this.events = options.events;
            this.$el.addClass('magictime');
            this.bindEvents();

            new tuna.view.MainImageView({
                el: this.$('.thecodeine_admin_main_image')
            });
        },

        bindEvents: function () {
            this.events.on('backgroundJobStart', _.bind(function(){
                $('body').addClass('sending');
                this.$('button[type="submit"]').attr('disabled', true);
            }, this));

            this.events.on('backgroundJobEnd', _.bind(function(){
                $('body').removeClass('sending');
                this.$('button[type="submit"]').attr('disabled', false);
            }, this));
        },

        _onGalleryOpen: function () {
            $('.admin-gallery-container').trigger('open');
        },

        _onAttachmentsOpen: function () {
            $('.admin-attachments-container').trigger('open');
        }
    });
})();
