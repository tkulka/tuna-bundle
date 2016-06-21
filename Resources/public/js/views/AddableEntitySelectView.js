tuna.view.AddableEntitySelectView = Backbone.View.extend({
    events: {
        'change [data-addable-entity-select]': 'onSelectChange'
    },
    initialize: function () {
        this.updateNewForm($('[data-addable-entity-select]'));

    },
    onSelectChange: function (event) {
        this.updateNewForm($(event.currentTarget));
    },
    updateNewForm: function ($select) {
        var $newForm = this.$('.new-value');

        if ($select.val() == 'new') {
            $newForm.slideDown(100);
        } else {
            $newForm.slideUp(100);
        }
    }
});
