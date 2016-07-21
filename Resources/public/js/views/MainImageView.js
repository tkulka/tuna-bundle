(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },

        uploadCallback: function (response) {
            this.$('.thecodeine_admin_main_image .image').html('<div><img src="'+response.path+'"><span class="remove">Usuń</span></div>');
        },

        onRemove: function (event) {
            this.$('.image').empty();
        }
    });
})();
