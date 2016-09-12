(function () {
    tuna.view.NestedSortableView = Backbone.View.extend({
        events: {
            'click [data-action="save-order"]': 'onSaveOrderClick'
        },

        initialize: function () {
            this.saveOrderUrl = this.$el.data('nestedsortableurl');
            this.$list = this.$('[data-nested-sortable] .root > ul');
            this.bindEvents();
        },

        bindEvents: function () {
            var rootID = this.$('.root').data('root-id');
            this.$list.nestedSortable({
                listType: 'ul',
                handle: 'div',
                items: 'li',
                toleranceElement: '> div',
                excludeRoot: true,
                rootID: rootID,
                placeholder: 'sortable-placeholder',
                update: _.bind(function (event, ui) {
                    this.$('[data-action="save-order"]').removeClass('inactive');
                }, this)
            });
        },

        onSaveOrderClick: function (event) {
            event.preventDefault();
            var order = this.$list.sortable('toArray');
            order = _.map(order, function (item) {
                item.depth = item.depth + 1;

                return item;
            });
            $.ajax({
                type: 'POST',
                data: {
                    order: order
                },
                url: this.saveOrderUrl,
                success: function (data) {
                    $(event.currentTarget).addClass('inactive');
                }
            })
        }
    });
})();
