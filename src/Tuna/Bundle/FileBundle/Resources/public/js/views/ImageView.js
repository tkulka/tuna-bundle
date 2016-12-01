(function () {
    tuna.view.ImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },

        onRemove: function () {
            this.$('.preview').empty();
            this.$('.input--path, .input--filename').val('');
        }
    });
})();
