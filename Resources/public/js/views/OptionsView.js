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
            this.tunaEvents.on('uploadStart', _.bind(function(){
                this.toggleSubmit();
            }, this));

            this.tunaEvents.on('uploadEnd', _.bind(function(){
                this.toggleSubmit();
            }, this));
        },

        toggleSubmit: function () {
            var btn = this.$('button[type="submit"]')[0];
            btn.disabled = btn.disabled ? false : true;
        },

        _onGalleryOpen: function () {
            $('.admin-gallery-container').trigger('open');
        },

        _onAttachmentsOpen: function () {
            $('.admin-attachments-container').trigger('open');
        }
    });
})();
