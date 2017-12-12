var zikulatrivialmodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=zikulatrivialmodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/zikulatrivial/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Trivial')
        ;

        button.click(function() {
            ZikulaTrivialModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
