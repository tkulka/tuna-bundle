var jms = JMSTranslationManager;
JMSTranslationManager = function() {
    jms.call(this, updateMessagePath, isWritable);

    $('[data-action="refresh-translations"]').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            url: 'refresh',
            beforeSend: function() {
                $this.closest('.admin-option-container').find('.refresh-message').remove();
            },
            error: function() {
                $this.before('<div class="refresh-message error">' + Translator.trans("Couldn't refresh translations.") + '</div>');
            },
            success: function() {
                $this.before('<div class="refresh-message">' + Translator.trans('Translations refreshed.') + '</div>');
            }
        })
    });

    this.translation.ajax = {
        type: 'POST',
        headers: {'X-HTTP-METHOD-OVERRIDE': 'PUT'},
        dataMethod: 'PUT',
        beforeSend: function(data, event, JMS)
        {
            var $elem = $(event.target);
            $elem.parent().children('.alert-message').remove();
            $elem.parent().append(JMS.translation.ajax.progressMessageContent);
        },
        error: function(data, event, JMS)
        {
            var $elem = $(event.target);
            $elem.parent().append(JMS.translation.ajax.errorMessageContent);
        },
        errorMessageContent: '<span class="alert-message label error">' + Translator.trans('Could not be saved.') + '</span>',
        success: function(data, event, JMS)
        {
            var $elem = $(event.target);

            if (data == 'Translation was saved')
            {
                $elem.parent().children('.alert-message').remove();
                $elem.parent().append(JMS.translation.ajax.savedMessageContent);
            } else
            {
                $elem.parent().children('.alert-message').remove();
                $elem.parent().append(JMS.translation.ajax.unsavedMessageContent);
            }
        },
        progressMessageContent: '<span class="alert-message label success">' + Translator.trans('Saving...') + '</span>',
        savedMessageContent: '<span class="alert-message label success">' + Translator.trans('Translation was saved.') + '</span>',
        unsavedMessageContent: '<span class="alert-message label error">' + Translator.trans('Could not be saved.') + '</span>',
        complete: function(data, event, JMS)
        {
            var $elem = $(event.target);
            var $parent = $elem.parent();
            $elem.data('timeoutId', setTimeout(function ()
            {
                $elem.data('timeoutId', undefined);
                $parent.children('.alert-message').fadeOut(300, function ()
                {
                    var $message = $(this);
                    $message.remove();
                });
            }, 10000));
        }
    };
};