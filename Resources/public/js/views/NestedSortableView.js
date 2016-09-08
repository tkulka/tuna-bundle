(function () {
    tuna.view.NestedSortableView = Backbone.View.extend({
        events: {
            'click [data-action="save-order"]': 'onSaveOrderClick'
        },
        initialize: function () {
            this.saveOrderUrl = this.$el.data('nestedsortableurl');
            this.$list = this.$('[data-nested-sortable]');
            this.bindEvents();
        },
        bindEvents: function () {
            this.$list.nestedSortable({
                listType: 'ul',
                handle: 'div',
                items: 'li:not(.disabled)',
                toleranceElement: '> div',
                protectRoot: true,
                excludeRoot: true,
                placeholder: 'sortable-placeholder',
                update: _.bind(function (event, ui) {
                    this.$('[data-action="save-order"]').removeClass('inactive');
                }, this)
            });
        },
        onSaveOrderClick: function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                data: {
                    order: this.$list.sortable('toArray')
                },
                url: this.saveOrderUrl,
                success: function (data) {
                    $(event.currentTarget).addClass('inactive');
                }
            })
        }
    });
})();
