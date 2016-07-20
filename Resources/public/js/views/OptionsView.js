(function () {
    tuna.view.OptionsView = Backbone.View.extend({
        events: {
            'click .btn-gallery': '_onGalleryOpen',
            'click .btn-attachments': '_onAttachmentsOpen'
        },

        initialize: function () {
            this.$el.addClass('magictime');
            this.initDropzone();

            new tuna.view.MainImageView({
                el: this.$('.thecodeine_admin_main_image')
            });
        },
        initDropzone: function () {
            var oThis = this;

            this.$el.dropzone({
                url: '/admin/news/image/upload',
                maxFilesize: 2,
                acceptedFiles: '.jpg, .jpeg',
                paramName: 'the_codeine_image_request[file]',
                clickable: '.thecodeine_admin_main_image .btn',
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
            }).on('dragbetterenter', function() {
                oThis.$el.addClass('drag-over');
            }).on('dragbetterleave', function() {
                oThis.$el.removeClass('drag-over');
            });
        },
        showImagePreview: function (response) {
            this.$('.thecodeine_admin_main_image .image').html('<div><img src="'+response.path+'"><span class="remove">Usuń</span></div>');
        },
        onSending: function () {
            this.$('.main-image .btn').toggleClass('loading');
        },
        _onGalleryOpen: function () {
            $('.admin-gallery-container').trigger('open');
        },

        _closeGallery: function () {
            $('.admin-gallery-container').trigger('close');
        },

        _onAttachmentsOpen: function () {
            $('.admin-attachments-container').trigger('open');
        },

        _closeAttachments: function () {
            $('.admin-attachments-container').trigger('close');
        }
    });
})();
