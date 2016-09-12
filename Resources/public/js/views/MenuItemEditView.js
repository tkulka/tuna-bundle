(function () {
    tuna.view.MenuItemEditView = Backbone.View.extend({
        events: {
            'change .form--page select': 'onLinkedPageChange'
        },

        initialize: function () {
            this.changeLinkedPage(this.$('.form--page select').val());
        },

        onLinkedPageChange: function (event) {
            this.changeLinkedPage($(event.currentTarget).val());
        },

        changeLinkedPage: function (value) {
            this.$('.form--path, .form--externalUrl').toggleClass('hide', value != '');
        }
    });
})();
