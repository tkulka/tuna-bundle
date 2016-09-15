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
            var menuTreeView = this;

            this.$list.nestedSortable({
                listType: 'ul',
                handle: 'div:not(.sortable-placeholder)',
                items: 'li',
                toleranceElement: '> div',
                excludeRoot: true,
                rootID: this.$('.root').data('id'),
                placeholder: 'sortable-placeholder',
                update: _.bind(function (event, ui) {
                    this.$('[data-action="save-order"]').removeClass('inactive');
                }, this)
            });

            this.$('li[data-id] > div')
                .on('dragover', function (event) {
                    if (event.preventDefault) event.preventDefault();

                    return false;
                })
                .on('dragenter', function () {
                    $(this).addClass('sortable-placeholder');
                    return false;
                })
                .on('dragleave', function () {
                    $(this).removeClass('sortable-placeholder');
                })
                .on('drop', function (event) {
                    if (event.stopPropagation) event.stopPropagation();

                    $(this).removeClass('sortable-placeholder');
                    $.ajax({
                        type: 'post',
                        url: menuTreeView.$('[data-add-menu-item-url]').data('addMenuItemUrl'),
                        data: {
                            pageId: event.originalEvent.dataTransfer.getData('id'),
                            menuParentId: $(this).closest('[data-id]').data('id')
                        },
                        success: function () {
                            location.reload();
                        }
                    });
                });

            this.$('.standalone-pages tr[data-id]')
                .attr('draggable', true)
                .on('dragstart', function (event) {
                    event.originalEvent.dataTransfer.setData('id', $(this).data('id'));
                });
        },

        onSaveOrderClick: function (event) {
            event.preventDefault();
            var order = this.$list.sortable('toArray');
            order = _.map(order, function (item) {
                // weird ifology due to weird behavior of nestedSortable plugin
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
