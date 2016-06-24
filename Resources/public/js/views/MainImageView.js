(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'change input[type="file"]': 'onChange',
            'click [data-action="remove"]': 'onRemove'
        },
        onChange: function (event) {
            this.previewImage(event.currentTarget);
        },
        onRemove: function (event) {
            this.$('.image').empty();
            this.$('input').val('');
            this.$('.remove-image').val('1');
        },
        previewImage: function (input) {
            if (!this.$('.image img').length) {
                this.$('.image').prepend($('<img><span class="remove">Usuń</span>'));
            }

            var $img = this.$('.image img');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = _.bind(function (e) {
                    $img.attr('src', e.target.result);
                    this.$('.remove-image').val('0');
                }, this);

                reader.readAsDataURL(input.files[0]);
            }
        }
    });
})();
