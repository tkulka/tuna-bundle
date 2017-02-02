CKEDITOR.editorConfig = function (config) {
    config.fixedUI = {
        el: '.admin-container .inside',
        offset: 50
    };

    config.autoGrow_onStartup = true;
    config.autoGrow_minHeight = 250;
};