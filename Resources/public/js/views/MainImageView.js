(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },
        initialize: function () {
            this.options = this.$el.data('dropzone-options');

            new tuna.view.DropzoneView({
                el: $(this.options.selector),
                options: this.options,
                oThis: this
            });
        },
        onRemove: function () {
            this.$('.preview').empty();
            this.$('input.form--path, input.form--filename').val('');
        },

        uploadCallback: function (response) {
           this.$('input.form--path').val(response.path);
           this.$('input.form--filename').val(response.originalName);
           this.$('.preview').html(
               this.options.previewTemplate.replace("__path__", response.path)
           );
        }
    });
})();
