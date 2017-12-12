/**
 * Initializes the plugin, this will be executed after the plugin has been created.
 * This call is done before the editor instance has finished it's initialization so use the onInit event
 * of the editor instance to intercept that event.
 *
 * @param {tinymce.Editor} ed Editor instance that the plugin is initialised in
 * @param {string} url Absolute URL to where the plugin is located
 */
tinymce.PluginManager.add('zikulatrivialmodule', function(editor, url) {
    var icon;

    icon = Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/zikulatrivial/images/admin.png';

    editor.addButton('zikulatrivialmodule', {
        //text: 'Trivial',
        image: icon,
        onclick: function() {
            ZikulaTrivialModuleFinderOpenPopup(editor, 'tinymce');
        }
    });
    editor.addMenuItem('zikulatrivialmodule', {
        text: 'Trivial',
        context: 'tools',
        image: icon,
        onclick: function() {
            ZikulaTrivialModuleFinderOpenPopup(editor, 'tinymce');
        }
    });

    return {
        getMetadata: function() {
            return {
                title: 'Trivial',
                url: 'https://www.heroesofmightandmagic.es'
            };
        }
    };
});
