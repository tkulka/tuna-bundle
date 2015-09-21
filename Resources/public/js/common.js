window.tuna || (window.tuna = {
    locale: 'pl',
    website: {},
    backbone: {},
    templates: {},
    view: {
        helpers: {}
    },
    model: {},
    collection: {},
    router: {},
    features: {}
});

/**
 * Main admin website object
 *
 */
tuna.website = {
    init: function() {

        //init main views
        new tuna.view.NavigationView({el: $('nav')[0]});
        new tuna.view.ListView({el: $('.admin-list')[0]});

        //OPTIONS
        if( $('.admin-option-container').size()) {
            new tuna.view.OptionsView({el: $('.admin-option-container')[0]});
            $('.btn-options').click(function(e){
                e.stopPropagation();
                $('.admin-option-container').trigger('open');
            });
        }
        //WYSIWYG EDITOR
        $('.thecodeine_admin_editor').each(function(){
            new tuna.view.EditorView({el:  $(this)[0] });
        });

        //GALLERY
        if($('.admin-gallery-container').size()) new tuna.view.GalleryView({el: $('.admin-gallery-container')[0]});
        //ATTACHMENTS
        if($('.admin-attachments-container').size()) new tuna.view.AttachmentsView({el: $('.admin-attachments-container')[0]});


        //setup minimal height for main container
        this.resizeContainer();
        $(window).resize(tuna.website.resizeContainer);
    },

    resizeContainer: function() {
        var height = $(window).height() - $('nav').height() - $('.admin-edit-footer').height();
        $('section.main_container').css({
            'min-height': height,
            'height'    : height
        });
        $('div[data-dynamic-height="1"]').height(height + 3);
    },

    goToUri: function(uri) {
        top.location.href = uri;
    }
}

/**
 * Main admin menu view
 *
 * @type {*|void}
 */
tuna.view.NavigationView = Backbone.View.extend({
   events: {
       'change select': "onSelectChange"
   },

   onSelectChange: function(e)  {
       tuna.website.goToUri($(e.target).val());
   }
});

/**
 * Items Lists
 *
 * @type {*|void}
 */
tuna.view.ListView = Backbone.View.extend({
    events: {
        'click [data-action="delete"]': "onDeleteItem"
    },

    onDeleteItem: function(e)  {
        $('#modalConfirm .modal-body p').html(
            'Czy na pewno chcesz usunąć <br/> <b>' + $(e.target).data('title') + '</b>?'
        )
        $('#modalConfirm').modal({
            keyboard: true
        });
        $('#modalConfirm [data-action="accept"]')
            .data('url', $(this).data('url'))
            .off('click').on('click', function(){
                $.get($(e.target).data('url')).
                    done(function(){
                        location.reload();
                    })
                    .fail(function(){
                        alert('Error');
                    });
                $('#modalConfirm').modal('hide');
        });
    }
});


/**
 * Wysiwyg editor
 *
 * @type {*|void}
 */
tuna.view.EditorView = Backbone.View.extend({

    initialize: function() {
        var root = this;

        this.divEditorId    = this.$el.attr('id') + '-editor';
        this.$divEditor      = $( '#' + this.divEditorId );
        this.$editorToolbar  = $('div[data-target="#' + this.divEditorId + '"]');

        this.$divEditor
            .html(this.$el.val())
            .show()
            .wysiwyg();

        this.$el.hide();
        this.$editorToolbar.find('input[data-target="#pictureBtn"]').hide();

        this.$divEditor.on('blur', _.bind(this.onEditorChange, this));
        this.$editorToolbar.find('.dropdown-menu input').on('click', function(e){
            e.stopPropagation();
        });
        this.$editorToolbar.find('#pictureBtn').click(function(e) {
            e.preventDefault();
            root.$editorToolbar.find('input[data-target="#pictureBtn"]').trigger('click');
        });

        $('div[data-role="editor-toolbar"] .insertHTML-insertBtn').click(function(e){
            e.preventDefault();
            root._insertHtmlAtCursor($(this).parent().parent().find('.insertHTML-value').val());
            root.onEditorChange();
        });
        $('.insertHTML-value').click(function(e){
            e.preventDefault();
            e.stopPropagation();
        })

        $('input[data-edit="createLink"]').on('keydown', function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                $(this).parent().find('button').click();
                return false;
            }
        })

        //remove bad html when pasting to editor
        $(document).on('paste', '.admin-wysiwyg-editor', function(e) {
            var html = (e.originalEvent || e).clipboardData.getData('text/html') || (e.originalEvent || e).clipboardData.getData('text/plain');

            document.execCommand('insertHTML', false, $.htmlClean(html, {
                format: false,
                replace: [['h1','h3'],'h2'],
                removeAttrs: ['class', 'style', "font"],
                allowedAttributes: ["width", "height","src", "frameborder","allowfullscreen"],
                allowedTags: ['p','i','b','u','strong', 'iframe', "ul", "li"],
                removeTags: ["basefont", "center", "dir", "font", "frame", "frameset", "isindex", "menu", "noframes", "s", "strike","br", "canvas", "hr", "img"],
                allowEmpty: ['iframe'],
                tagAllowEmpty: ['iframe'],
                allowComments: false,
            }));

            root.onEditorChange();
            e.preventDefault();
        })
    },

    onEditorChange: function() {
        this.$el.html( this.$divEditor.html() );
    },

    _insertHtmlAtCursor: function(html) {
        var sel, range;
        var htmlContainer = document.createElement("span");
        htmlContainer.innerHTML = html;
        if (window.getSelection) {
            sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();
                range.insertNode( htmlContainer );
            } else {
                $('.admin-wysiwyg-editor:eq(0)').append(html);
            }
        } else if (document.selection && document.selection.createRange) {
            document.selection.createRange().innerHTML = htmlContainer.innerHtml;
        }
    }
});

tuna.view.OptionsView = Backbone.View.extend({
    events: {
        'click .close': "_onClose",
        'close': "_onClose",
        'open': "_onOpen",
        'click .btn-gallery': '_onGalleryOpen',
        'click .btn-attachments': '_onAttachmentsOpen'
    },

    initialize: function() {
        this.$el.addClass('magictime');
       // this.$elementsToAnimate = this.$('.form-group, h6, .btn-gallery, .btn-attachments, label');
    },

    _onClose: function(e) {
        this._closeGallery();
        this._closeAttachments();

        $('.thumbnail.empty div div:not(.new-image) input').closest('li').remove();

        this.$el
            .removeClass('perspectiveLeftRetourn')
            .addClass('perspectiveLeft');

        $('body').off('click.options-watcher');
    },

    _onOpen: function(e) {
        this.$el
            .removeClass('perspectiveLeft')
            .show()
            .addClass('perspectiveLeftRetourn');

        var oThis = this;
        setTimeout(function(){

            $(document).on('mouseup.temporary',function (event) {
                if (!oThis.$el.is(event.target) && !$('.admin-gallery-container').is(event.target) && !$('.admin-attachments-container').is(event.target)
                    && oThis.$el.has(event.target).length === 0 && $('.admin-gallery-container').has(event.target).length === 0
                    && $('.admin-attachments-container').has(event.target).length === 0)
                {
                    oThis._onClose(e);
                    $(document).unbind('mouseup.temporary');
                } else {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

        },100);
    },

    _onGalleryOpen: function() {
        $('.admin-gallery-container').trigger('open');
    },

    _closeGallery: function() {
        $('.admin-gallery-container').trigger('close');
    },

    _onAttachmentsOpen: function() {
        $('.admin-attachments-container').trigger('open');
    },

    _closeAttachments: function() {
        $('.admin-attachments-container').trigger('close');
    }
});

tuna.view.GalleryView = Backbone.View.extend({

    events: {
        "click .add_new_image": "_onAddNewImage",
        "click .delete": "_onClickDelete",
        "change input[type='file']": "_onInputFileChange",
        'click .close': "_onClose",
        'close': "_onClose",
        'open': "_onOpen",
        'click': '_onClick'
    },

    initialize: function() {
        this.$el.addClass('magictime');
        this.$('.gallery-images li').hide();
        this._initSortable();
    },

    _onClick: function(e) {
        e.stopPropagation();
    },

    _initSortable: function() {
        var oThis = this;
        this.$('.gallery-images')
            .sortable()
            .bind('sortupdate', function() {
                oThis.recalculateImagePositions();
            });
    },

    _destroySortable: function() {
        this.$('.gallery-images').sortable('destroy');
    },

    _onClose: function() {
        this.$el.removeClass('slideLeftRetourn').addClass('holeOut');
        this.$('.gallery-images li').each(function(){
           $(this).removeClass('jelly-in').hide();
           if(!$(this).find('img').size()) {
         //      $(this).remove();
           }
        });
    },

    _onOpen: function() {
        $('.admin-attachments-container').trigger('close');
        this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');

        setTimeout(function(){
            $('.gallery-images li:not(.jelly-in)').each(function(idx){
                $(this)
                    .show()
//                    .css({
//                      //  '-webkit-transition-delay': idx * 100 + 's',
//                        'transition-delay': (idx * 100) + 's'
//                    })
                    .addClass('jelly-in');
            });
        }, 800);

    },

    recalculateImagePositions: function(){
        this.$('input.position').each(function(idx){
            $(this).val(idx);
        });
    },

    _onAddNewImage: function(e) {
        this._destroySortable();

        var prototype = $(e.currentTarget).data('prototype');
        // get the new index
        var index = $(e.currentTarget).data('index');
        index = index ? index : $('li.thumbnail').size();
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $(e.currentTarget).data('index', index + 1);

        this.$('.gallery-images').prepend($(newForm).addClass('jelly-in'));
        this.recalculateImagePositions();
        this._initSortable();
    },

    _onClickDelete: function(e) {
        var $element = $(e.currentTarget).parent();
        $element.removeClass('jelly-in').addClass('magictime holeOut');
        setTimeout(function(){$element.remove()}, 800);
    },

    _onInputFileChange: function(e) {
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
            reader.onload = (function(theFile) {
                return function(event) {
                    // Render thumbnail.
                    var $cnt = $element.parent();
                    $cnt.css({
                        'background-image': 'url('+event.target.result+')',
                        'background-size' : 'cover',
                        height: '178px',
                        width: '258px',
                        position: 'absolute',
                        top: 0,
                        left: 0,
                        'zIndex': 9
                    })
                    $cnt.addClass('jelly-in new-image');
                }
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }
});

tuna.view.AttachmentsView = Backbone.View.extend({

    events: {
        "click .add_new_attachment": "_onAddNewAttachment",
        "click .delete": "_onClickDelete",
        'click .close': "_onClose",
        'close': "_onClose",
        'open': "_onOpen",
        'click': "_onClick"
    },

    initialize: function() {
        this.$el.addClass('magictime');
        this.$('.attachments li').hide();
        this._initSortable();
    },

    _onClose: function() {
        this.$el.removeClass('slideLeftRetourn').addClass('holeOut');

        this.$('.attachments li').each(function(){
            $(this).removeClass('jelly-in').hide();
            if(!$(this).find('input[type="text"]').val()) {
                $(this).remove();
            }
        });
    },

    _onOpen: function() {
        $('.admin-gallery-container').trigger('close');
        this.$el.removeClass('holeOut').show().addClass('slideLeftRetourn');
        setTimeout(function(){
            $('.attachments li:not(.jelly-in)').each(function(idx){
                $(this)
                    .show()
                    .addClass('jelly-in');
            });
        }, 800);
    },

    recalculateImagePositions: function(){
        this.$('input.position').each(function(idx){
            $(this).val(idx);
        });
    },

    _initSortable: function() {
        var oThis = this;
        this.$('.attachments')
            .sortable()
            .bind('sortupdate', function() {
                oThis.recalculateImagePositions();
            });
    },

    _destroySortable: function() {
        this.$('.attachments').sortable('destroy');
    },

    _onClick: function(e) {
        e.stopPropagation();
    },

    _onAddNewAttachment: function(e) {
        this._destroySortable();

        var prototype = $(e.currentTarget).data('prototype');
        // get the new index
        var index = $(e.currentTarget).data('index');
        index = index ? index : $('li.item').size();
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        $(e.currentTarget).data('index', index + 1);

        this.$('.attachments').prepend($(newForm).addClass('jelly-in'));
        this.recalculateImagePositions();
        this._initSortable();
    },

    _onClickDelete: function(e) {
        var $element = $(e.currentTarget).parent();
        $element.removeClass('jelly-in').addClass('magictime holeOut');
        setTimeout(function(){$element.remove()}, 800);
    }

});
