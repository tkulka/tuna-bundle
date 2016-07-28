(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },
        initialize: function () {
            this.options = this.$el.data('dropzone-options');
        },
        onRemove: function () {
            this.$('.preview').empty();
            this.$('input.form--path, input.form--filename').val('');
        }
    });
})();
