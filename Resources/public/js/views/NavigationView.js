(function () {
    /**
     * Main admin menu view
     *
     * @type {*|void}
     */
    tuna.view.NavigationView = Backbone.View.extend({
        events: {
            'change select': "onSelectChange"
        },

        onSelectChange: function (e) {
            tuna.website.goToUri($(e.target).val());
        }
    });
})();
