(function () {
    /**
     * Items Lists
     *
     * @type {*|void}
     */
    tuna.view.ListView = Backbone.View.extend({
        events: {
            'click [data-action="delete"]': 'onDeleteItem'
        },

        onDeleteItem: function (event) {
            event.preventDefault();
            var $a = $(event.currentTarget);

            tuna.website.confirmModal(Translator.trans('Are you sure you want to delete') + ' <b>' + $a.data('title') + '</b>?').then(function () {
                window.location.href = $a.data('url');
            });
        }
    });
})();
