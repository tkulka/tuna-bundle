(function () {
    tuna.view.MainImageView = Backbone.View.extend({
        events: {
            'click [data-action="remove"]': 'onRemove'
        },
        initialize: function () {
            this.initDropzone();
        },
        initDropzone: function () {
            var oThis = this;

           this.$('.main-image').dropzone({
                url: '/admin/news/image/upload',
                maxFilesize: 2,
                acceptedFiles: '.jpg, .jpeg',
                paramName: 'the_codeine_image_request[file]',
                clickable: '.main-image .btn',
                maxFiles: 1,
                addedfile: function () {},
                error: function (file, errorMessage) {
                    oThis.onSending();
                    alert(errorMessage);
                },
                init: function () {
                    this.on("success", function(file, response) {
                        oThis.showImagePreview(response);
                    });

                    this.on("queuecomplete", function () {
                        oThis.onSending();
                        this.removeAllFiles();
                    });

                    this.on("sending", function() {
                        oThis.onSending();
                    });
                }
            });
        },
        showImagePreview: function (response) {
            this.$('.main-image .image').html('<div><img src="'+response.path+'"><span class="remove">Usuń</span></div>');
        },
        onSending: function () {
            this.$('.main-image .btn').toggleClass('loading');
        },
        onRemove: function (event) {
            this.$('.image').empty();
        }
    });
})();
