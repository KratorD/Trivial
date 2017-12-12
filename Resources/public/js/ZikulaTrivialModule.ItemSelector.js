'use strict';

var zikulaTrivialModule = {};

zikulaTrivialModule.itemSelector = {};
zikulaTrivialModule.itemSelector.items = {};
zikulaTrivialModule.itemSelector.baseId = 0;
zikulaTrivialModule.itemSelector.selectedId = 0;

zikulaTrivialModule.itemSelector.onLoad = function (baseId, selectedId) {
    zikulaTrivialModule.itemSelector.baseId = baseId;
    zikulaTrivialModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#zikulaTrivialModuleObjectType').change(zikulaTrivialModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(zikulaTrivialModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(zikulaTrivialModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(zikulaTrivialModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(zikulaTrivialModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(zikulaTrivialModule.itemSelector.onParamChanged);
    jQuery('#zikulaTrivialModuleSearchGo').click(zikulaTrivialModule.itemSelector.onParamChanged);
    jQuery('#zikulaTrivialModuleSearchGo').keypress(zikulaTrivialModule.itemSelector.onParamChanged);

    zikulaTrivialModule.itemSelector.getItemList();
};

zikulaTrivialModule.itemSelector.onParamChanged = function () {
    jQuery('#ajaxIndicator').removeClass('hidden');

    zikulaTrivialModule.itemSelector.getItemList();
};

zikulaTrivialModule.itemSelector.getItemList = function () {
    var baseId;
    var params;

    baseId = zikulaTrivialModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('zikulatrivialmodule_ajax_getitemlistfinder'), params, function (data) {
        var baseId;

        baseId = zikulaTrivialModule.itemSelector.baseId;
        zikulaTrivialModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        zikulaTrivialModule.itemSelector.updateItemDropdownEntries();
        zikulaTrivialModule.itemSelector.updatePreview();
    });
};

zikulaTrivialModule.itemSelector.updateItemDropdownEntries = function () {
    var baseId, itemSelector, items, i, item;

    baseId = zikulaTrivialModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = zikulaTrivialModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (zikulaTrivialModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(zikulaTrivialModule.itemSelector.selectedId);
    }
};

zikulaTrivialModule.itemSelector.updatePreview = function () {
    var baseId, items, selectedElement, i;

    baseId = zikulaTrivialModule.itemSelector.baseId;
    items = zikulaTrivialModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (zikulaTrivialModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == zikulaTrivialModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

zikulaTrivialModule.itemSelector.onItemChanged = function () {
    var baseId, itemSelector, preview;

    baseId = zikulaTrivialModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(zikulaTrivialModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    zikulaTrivialModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};

jQuery(document).ready(function () {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    zikulaTrivialModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
