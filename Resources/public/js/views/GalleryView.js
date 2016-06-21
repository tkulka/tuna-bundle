tuna.view.GalleryView = Backbone.View.extend({

    events: {
        "click .add_new_item": "_onAddNewItem",
        "click .delete": "_onClickDelete",
        "change input[type='file']": "_onInputFileChange",
        'click .close': "_onClose",
        'close': "_onClose",
        'open': "_onOpen",
        'click': '_onClick',
        'click .a2lix_translationsLocales li a': "_onLanguageChange"
    },

    initialize: function () {
        this.$el.addClass('magictime');
        this._initSortable();
    },

    _onClick: function (e) {
        e.stopPropagation();
    },

    _initSortable: function () {
        var oThis = this;
        this.$('.gallery-items')
            .sortable({
                handle: '.handle'
            })
            .disableSelection()
            .bind('sortupdate', function () {
                oThis.recalculateImagePositions();
            });
    },

    _onClose: function () {
        this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
    },

    _onOpen: function () {
        $('.admin-attachments-container').trigger('close');
        this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');
    },

    recalculateImagePositions: function () {
        this.$('input.position').each(function (idx) {
            $(this).val(idx);
        });
    },

    choiceEventListener: function (index) {
        var oThis = this;

        if ($('#thecodeine_pagebundle_page_gallery_items_' + index + '_type').length > 0) {
            var id = '#thecodeine_pagebundle_page_gallery_items_';
        } else if ($('#thecodeine_newsbundle_news_gallery_items_' + index + '_type').length > 0) {
            var id = '#thecodeine_newsbundle_news_gallery_items_';
        }

        var $type = $(id + index + '_type');

        // When sport gets selected ...
        $type.change(function () {
            $type.hide();
            // ... retrieve the corresponding form.
            var $form = $(this).closest('form');
            // Simulate form data, but only include the selected sport value.
            var data = $form.serialize();
            // Submit data via AJAX to the form's action path.
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: data,
                success: function (html) {
                    // Replace current position field ...
                    $(id + index).replaceWith(
                        // ... with the returned one from the AJAX response.
                        $(html).find(id + index)
                    );
                }
            });
        });
    },

    _onAddNewItem: function (e) {
        var prototype = $(e.currentTarget).data('prototype');
        // get the new index
        var index = $(e.currentTarget).data('index');
        index = index ? index : $('li.item').size();
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $(e.currentTarget).data('index', index + 1);

        this.$('.gallery-items').prepend($(newForm));
        this.choiceEventListener(index);
    },

    _onClickDelete: function (e) {
        $(e.currentTarget).parent().remove()
    },

    _onInputFileChange: function (e) {
        var $element = $(e.currentTarget);
        var files = e.target.files; // FileList object
        $element.parent().removeClass('jelly-in');
        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (event) {
                    // Render thumbnail.
                    var $cnt = $element.parent();
                    $cnt.css({
                        'background-position': 'center center',
                        'background-image': 'url(' + event.target.result + ')',
                        'background-size': 'cover',
                        height: '85px',
                        width: '180px',
                        position: 'relative',
                        top: 0,
                        left: 0,
                        'zIndex': 9
                    });
                }
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    },

    _onLanguageChange: function (e) {
        Backbone.trigger('LanguageChange', e);
    }
});
