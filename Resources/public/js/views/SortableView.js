(function () {
    function lockWidths($table) {
        $table.find('td, th').each(function (i, item) {
            $(item).width($(item).width());
        });
    }

    function unlockWidths($table) {
        $table.find('td, th').each(function (i, item) {
            $(item).width('');
        });
    }

    tuna.view.SortableView = Backbone.View.extend({
        events: {
            'click [data-action="save-order"]': 'onSaveOrderClick'
        },
        initialize: function () {
            this.$tbody = this.$('tbody');

            this.$tbody.sortable({
                handle: '.handle',
                stop: function (event, ui) {
                    unlockWidths($(ui.item).closest('table'));
                },
                change: _.bind(function (event, ui) {
                    this.$('[data-action="save-order"]').fadeIn();
                }, this)
            }).find('.handle').on('mousedown', function () {
                lockWidths($(this).closest('table'));
            });
        },
        onSaveOrderClick: function (event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                data: {
                    order: this.$tbody.sortable('toArray', {attribute: 'data-id'})
                },
                url: this.$el.data('sortable-url'),
                success: function (data) {
                    $(event.currentTarget).fadeOut();
                }
            })
        }
    });
})();
