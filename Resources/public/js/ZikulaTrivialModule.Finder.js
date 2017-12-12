'use strict';

var currentZikulaTrivialModuleEditor = null;
var currentZikulaTrivialModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getZikulaTrivialModulePopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes';
}

/**
 * Open a popup window with the finder triggered by an editor button.
 */
function ZikulaTrivialModuleFinderOpenPopup(editor, editorName) {
    var popupUrl;

    // Save editor for access in selector window
    currentZikulaTrivialModuleEditor = editor;

    popupUrl = Routing.generate('zikulatrivialmodule_external_finder', { objectType: 'tournament', editor: editorName });

    if (editorName == 'ckeditor') {
        editor.popup(popupUrl, /*width*/ '80%', /*height*/ '70%', getZikulaTrivialModulePopupAttributes());
    } else {
        window.open(popupUrl, '_blank', getZikulaTrivialModulePopupAttributes());
    }
}


var zikulaTrivialModule = {};

zikulaTrivialModule.finder = {};

zikulaTrivialModule.finder.onLoad = function (baseId, selectedId) {
    if (jQuery('#zikulaTrivialModuleSelectorForm').length < 1) {
        return;
    }
    jQuery('select').not("[id$='pasteAs']").change(zikulaTrivialModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(zikulaTrivialModule.finder.handleCancel);

    var selectedItems = jQuery('#zikulatrivialmoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        zikulaTrivialModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

zikulaTrivialModule.finder.onParamChanged = function () {
    jQuery('#zikulaTrivialModuleSelectorForm').submit();
};

zikulaTrivialModule.finder.handleCancel = function (event) {
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        zikulaTrivialClosePopup();
    } else if ('quill' === editor) {
        zikulaTrivialClosePopup();
    } else if ('summernote' === editor) {
        zikulaTrivialClosePopup();
    } else if ('tinymce' === editor) {
        zikulaTrivialClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function zikulaTrivialGetPasteSnippet(mode, itemId) {
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
zikulaTrivialModule.finder.selectItem = function (itemId) {
    var editor, html;

    html = zikulaTrivialGetPasteSnippet('html', itemId);
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        if (null !== window.opener.currentZikulaTrivialModuleEditor) {
            window.opener.currentZikulaTrivialModuleEditor.insertHtml(html);
        }
    } else if ('quill' === editor) {
        if (null !== window.opener.currentZikulaTrivialModuleEditor) {
            window.opener.currentZikulaTrivialModuleEditor.clipboard.dangerouslyPasteHTML(window.opener.currentZikulaTrivialModuleEditor.getLength(), html);
        }
    } else if ('summernote' === editor) {
        if (null !== window.opener.currentZikulaTrivialModuleEditor) {
            html = jQuery(html).get(0);
            window.opener.currentZikulaTrivialModuleEditor.invoke('insertNode', html);
        }
    } else if ('tinymce' === editor) {
        window.opener.currentZikulaTrivialModuleEditor.insertContent(html);
    } else {
        alert('Insert into Editor: ' + editor);
    }
    zikulaTrivialClosePopup();
};

function zikulaTrivialClosePopup() {
    window.opener.focus();
    window.close();
}

jQuery(document).ready(function () {
    zikulaTrivialModule.finder.onLoad();
});
