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

            tuna.website
                .confirmModal(Translator.trans('modal.question', {
                    'title': $a.data('title')
                }))
                .then(function () {
                    window.location.href = $a.data('url');
                });
        }
    });
})();
