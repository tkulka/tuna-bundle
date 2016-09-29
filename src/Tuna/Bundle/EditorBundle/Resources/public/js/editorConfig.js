/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.stylesSet.add( 'tuna_styles', [
    { name: 'h1', element: 'h1' },
    { name: 'h2', element: 'h2' },
    { name: 'p', element: 'p' },
    { name: 'blockquote', element: 'p', attributes: { 'class': 'blockquote' } }
]);

CKEDITOR.editorConfig = function (config) {
    config.toolbar = [
        { name: 'styles', items: ['Styles'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
        { name: 'paragraph', items: ['BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'links', items: ['Link'] },
        { name: 'insert', items: ['Image'] },
        { name: 'document', items: ['Source'] }
    ];

    config.toolbar_basic = [
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
        { name: 'document', items: ['Source'] }
    ];

    config.extraPlugins = 'divarea,autogrow,fixedUI,imageToolbar';

    config.fixedUI = {
        el: '.admin-container .inside',
        offset: 50
    };

    config.autoGrow_onStartup = true;
    config.autoGrow_minHeight = 250;

    config.skin = 'tuna';

    config.stylesSet = 'tuna_styles';

    config.contentsCss = '/bundles/thecodeineadmin/css/wysiwyg-styles.css';
};