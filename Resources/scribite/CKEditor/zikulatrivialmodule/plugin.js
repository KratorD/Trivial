CKEDITOR.plugins.add('zikulatrivialmodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertZikulaTrivialModule', {
            exec: function (editor) {
                ZikulaTrivialModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('zikulatrivialmodule', {
            label: 'Trivial',
            command: 'insertZikulaTrivialModule',
            icon: this.path.replace('scribite/CKEditor/zikulatrivialmodule', 'images') + 'admin.png'
        });
    }
});
