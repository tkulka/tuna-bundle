(function () {
    tuna.view.MenuItemEditView = Backbone.View.extend({
        events: {
            'change .form--page select': 'onLinkedPageChange'
        },

        initialize: function () {
            this.changeLinkedPage(this.$('.form--page select').val(), false);
        },

        onLinkedPageChange: function (event) {
            this.changeLinkedPage($(event.currentTarget).val(), true);
        },

        changeLinkedPage: function (value, updateTitles) {
            this.$('.form--path, .form--externalUrl').toggleClass('hide', value != '');

            if (updateTitles && pageTitlesMap[value]) {
                _.each(pageTitlesMap[value], function (title, locale) {
                    this.$('.a2lix_translations [data-lang="' + locale + '"] .form--label input').val(title);
                }, this);
            }
        }
    });
})();
