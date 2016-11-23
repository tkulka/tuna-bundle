(function () {
    /**
     * Edit Lists
     *
     * @type {*|void}
     */
    tuna.view.EditView = Backbone.View.extend({
        events: {
            'click .a2lix_translationsLocales li a': '_onLanguageChange'
        },

        initialize: function (options) {
            this.options = options;

            Backbone.on('LanguageChange', this._onLanguageChange, this);

            $(".datepicker")
                .datetimepicker({
                    dateFormat: "yy-mm-dd",
                    timeFormat: "HH:mm:ss",
                    defaultTime: '00:00:00',
                    showAnim: 'slideDown',
                    beforeShow: function (input, inst) {
                        var $dp = $(inst.dpDiv);
                        setTimeout(function () {
                            $dp.css({
                                marginLeft: 0,
                                marginTop: 0,
                                top: 0,
                                left: 0,
                                position: 'relative'
                            });
                        }, 0);
                        $(this).closest('.form-group').append($dp);
                    }
                })
                .datetimepicker('option', $.datepicker.regional[options.lang])
                .datetimepicker('option', 'dateFormat', 'yy-mm-dd')
                .datetimepicker('option', $.timepicker.regional[options.lang]);

        },

        _onLanguageChange: function (e) {

            var $tabContent = $('.tab-content');
            var target = $(e.target).data('target');

            $(".a2lix_translationsLocales li").removeClass('active').find("a[data-target='" + target + "']").parent().addClass('active');
            $tabContent.children().removeClass('active');
            $tabContent.find(target).addClass('active');
        }
    });
})();
