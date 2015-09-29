function smsDialog(id){

 var formid = "#sms-form-" + id;
    jQuery(formid).dialog({
        autoOpen: false,
        position: 'center',
        title: 'Send SMS',
        draggable: false,
        width: 300,
        height: 400,
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
        open: function(){
            jQuery('.ui-widget-overlay').bind('click',function(){
                jQuery(formid).dialog('close');
            })
        }

    });

        jQuery(formid).dialog("open");

    //jQuery(".ui-dialog-titlebar").hide();
}