(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },
        initialize: function () {
            var options = this.$('[data-dropzone-options]').data('dropzone-options');

            new tuna.view.DropzoneView({
                el: $(options.selector),
                options: options,
                oThis: this
            });
        },
        onRemove: function (event) {
            this.$('.image .preview').empty();
            this.$('.image .form--path input').val('');
            this.$('.image .form--filename input').val('');
        },

        uploadCallback: function (response) {
           this.$('.form--path input').val(response.path);
           this.$('.form--filename input').val(response.originalName);
           this.$('.preview').html('<div><img src="/uploads/tmp/'+response.path+'"><span class="remove">Usuń</span></div>');
        }
    });
})();
