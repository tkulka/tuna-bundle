(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },
        onRemove: function (event) {
            this.$('.image').empty();
        }
    });
})();
