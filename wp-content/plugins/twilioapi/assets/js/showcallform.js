function callDialog(id) {
    var formid = "#call-form-" + id;
    jQuery(formid).dialog({
        autoOpen: false,
        position: 'middle',
        title: 'Call Number',
        draggable: false,
        width: 400,
        height: 200,
        resizable: true,
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        },
        modal: true,
        open: function () {
            jQuery('.ui-widget-overlay').bind('click', function () {
                jQuery(formid).dialog('close');
            })
        }
    });


        jQuery(formid).dialog("open");


    //jQuery(".ui-dialog-titlebar").hide();
}
