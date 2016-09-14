(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': '_onGalleryOpen',
            'click .btn-attachments': '_onAttachmentsOpen'
        },

        initialize: function (options) {

            this.tunaEvents = options.tunaEvents;
            this.uploadQueue = 0;
            this.$el.addClass('magictime');
            this.bindEvents();

            new tuna.view.MainImageView({
                el: this.$('.thecodeine_admin_main_image')
            });
        },

        bindEvents: function () {
            this.tunaEvents.on('backgroundJobStart', _.bind(function(){
                $('body').addClass('sending');
                this.$('button[type="submit"]').attr('disabled', true);
                this.uploadQueue += 1;
            }, this));

            this.tunaEvents.on('backgroundJobEnd', _.bind(function(){
                this.uploadQueue -= 1;
                if (this.uploadQueue == 0) {
                    this.finishSending();
                }
            }, this));
        },

        finishSending: function() {
            $('body').removeClass('sending');
            this.$('button[type="submit"]').attr('disabled', false);
        },

        _onGalleryOpen: function () {
            $('.admin-gallery-container').trigger('open');
        },

        _onAttachmentsOpen: function () {
            $('.admin-attachments-container').trigger('open');
        }
    });
})();
