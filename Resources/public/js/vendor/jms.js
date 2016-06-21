var jms = JMSTranslationManager;
JMSTranslationManager = function() {
    jms.call(this, updateMessagePath, isWritable, infoMessages);

    this.translation.ajax = {
        type: 'POST',
        headers: {'X-HTTP-METHOD-OVERRIDE': 'PUT'},
        dataMethod: 'PUT',
        beforeSend: function(data, event, JMS)
        {
            var $elem = $(event.target);
            $elem.parent().children('.alert-message').remove();
        },
        error: function(data, event, JMS)
        {
            var $elem = $(event.target);
            $elem.parent().append(JMS.translation.ajax.errorMessageContent);
        },
        errorMessageContent: '<span class="alert-message label error">' + infoMessages.error + '</span>',
        success: function(data, event, JMS)
        {
            var $elem = $(event.target);

            if (data == 'Translation was saved')
            {
                $elem.parent().append(JMS.translation.ajax.savedMessageContent);
            } else
            {
                $elem.parent().append(JMS.translation.ajax.unsavedMessageContent);
            }
        },
        savedMessageContent: '<span class="alert-message label success">' + infoMessages.success + '</span>',
        unsavedMessageContent: '<span class="alert-message label error">' + infoMessages.error + '</span>',
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