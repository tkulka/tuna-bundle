(function () {
    tuna.view.MenuTreeView = Backbone.View.extend({
        events: {
            'click [data-action="save-order"]': 'onSaveOrderClick'
        },

        initialize: function () {
            this.saveOrderUrl = this.$el.data('save-order-url');
            this.$list = this.$('[data-menu-tree] .root > ul');
            this.bindEvents();
        },

        bindEvents: function () {
            this.$list.nestedSortable({
                listType: 'ul',
                handle: 'div',
                items: 'li',
                toleranceElement: '> div',
                excludeRoot: true,
                rootID: this.$('.root').data('id'),
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
                if (item.item_id) {
                    item.id = item.item_id;
                    delete item.item_id;
                } else {
                    item.depth = item.depth + 1;
                }

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
