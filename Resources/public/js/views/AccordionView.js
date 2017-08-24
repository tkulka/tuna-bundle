(function () {
    tuna.view.AccordionView = Backbone.View.extend({
        events: {
            'click [data-toggler]': 'onTogglerClick'
        },

        initialize: function (options) {
            this.$content = this.$('.accordion__content');
        },

        onTogglerClick: function (event) {
            var state = !this.$content.is(':visible');
            this.$el.toggleClass('accordion--opened', state);
            if (state) {
                this.$content.slideDown();
            } else {
                this.$content.slideUp();
            }
        }
    });
})();
