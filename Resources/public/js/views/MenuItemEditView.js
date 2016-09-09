(function () {
    tuna.view.MenuItemEditView = Backbone.View.extend({
        events: {
            'change .form--page select': 'onPageChange'
        },

        initialize: function () {
            this.changePage(this.$('.form--page select').val());
        },

        onPageChange: function (event) {
            this.changePage($(event.currentTarget).val());
        },

        changePage: function (value) {
            if (value != '') {
                this.$('.form--path, .form--externalUrl').slideUp();
            } else {
                this.$('.form--path, .form--externalUrl').slideDown();
            }
        }
    });
})();
