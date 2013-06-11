/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config ) {
config.toolbar = [
    ['Bold','Italic','Underline','Strike','Font','FontSize'],['JustifyLeft','JustifyCenter','JustifyRight'],['TextColor','BGColor'],['Link','Unlink'],
    ['NumberedList','BulletedList'],['Image','MediaEmbed'],['Undo','Redo'],['RemoveFormat']
    ],
config.removePlugins = 'elementspath'
config.allowedContent = true,
config.disableNativeSpellChecker = false,
config.height = 200;
;
};
