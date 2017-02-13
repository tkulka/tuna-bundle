(function () {
    tuna.view.EditHeaderView = Backbone.View.extend({
        initialize: function (options) {
            this.tunaEvents = options.tunaEvents;
            this.bindEvents();
        },

        bindEvents: function () {
            this.listenTo(this.tunaEvents, {
                'file.uploadStart': this.onFileUploadStart,
                'file.uploadEnd': this.onFileUploadEnd
            });
        },

        onFileUploadStart: function () {
            $('body').addClass('sending');
            this.disableSubmit(true);
        },

        onFileUploadEnd: function () {
            $('body').removeClass('sending');
            this.disableSubmit(false);
        },

        disableSubmit: function (value) {
            this.$('button[type="submit"]').prop('disabled', value);
        }
    });
})();
