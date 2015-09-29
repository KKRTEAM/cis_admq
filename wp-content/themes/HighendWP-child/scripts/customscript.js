jQuery(function(){

//For Upgrade functionality from Advisee to Advisor -Start
    jQuery( "#um-upgrade" ).click(function() {
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action:'custom_change_advisee_to_advisor',
                //dataType: "json",
                upgrade:'1'
            },
            error: function(errorThrown) {
                //jQuery('#pos').html("Could not save location");
                console.log(errorThrown);
            },
            success: function(response) {
                //jQuery('#pos').html("Location saved successfully");
                console.log(response);
                window.location = '/';
            },
            type: 'POST'
        });
        alert("Your account has been upgraded, please log back in to finish up your Advisory Profile.");

    });
//For Upgrade functionality from Advisee to Advisor -End

//For Skill form hide/show upload fields -Start
    jQuery('#skillLicense').hide();
    jQuery('#skillDegree').hide();
    jQuery('#skillLevel').change(function(){
        if(jQuery('#skillLevel').val() == 'Certified Professional') {
            jQuery('#skillDegree').show();
        } else {
            jQuery('#skillDegree').hide();
        }
        if(jQuery('#skillLevel').val() == 'Licensed To Practice') {
            jQuery('#skillLicense').show();
        } else {
            jQuery('#skillLicense').hide();
        }
    });
//For Skill form hide/show upload fields -End

//Skill Post Validation form -Start
    jQuery("#skillPostForm").validate({
        // Specify the validation rules
        debug: true,
        rules: {
            skillCategory: "required",
            skillTags: "required",
            skillLevel: "required",
            skillDegree: {

                required: true
            },
            skillLicense: {

                required: true
            },
            skillRate: "required"
        },

        // Specify the validation error messages
        messages: {
            skillCategory: "Please Select Category",
            skillTags: "Please enter tags for your skill",
            skillLevel: "Please Select Skill Proficiency Level",
            skillDegree: "Please select your certificate to upload",
            skillLicense: "Please select your license to upload",
            skillRate: "Please enter you hourly rate in USD"
        },
        errorElement : 'div',
        errorLabelContainer: '.errorTxt',

        submitHandler: function(form) {
            form.submit();
        }
    });
//Skill Post Validation form -End
    jQuery("#hb-submit-endcalling").click(function(e){
        e.preventDefault();
    });
    /*
     jQuery("#calling-panel").hide();
     });
     jQuery(".close_call").click(function(e){
     e.preventDefault();
     jQuery("#calling-panel").hide();
     });
     jQuery(".close_sms").click(function(e){
     e.preventDefault();
     jQuery("#message-panel").hide();
     });
     */
});

//Communication Functionality - Start
var comm_js = function(){
    mini_sms_form();
    communication_sms();
    mini_call_form();
    communication_call();
    communication_webrtc();
    advisor_webrtc();
};

jQuery(function(){
    "use strict";
    window.comm_js();
});

//Twilio Functionality - Start
//SMS - Start
function mini_sms_form() {
    var toggle = false;

    $j('.sms-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        var $panel = jQuery('#message-panel');
        jQuery("#calling-panel").hide();
        $panel.show();
        if($j('#hb-submit-message-panel-form').hasClass('hb-nephritis')){
            var reset_text = 'Send Message';
            $j('#hb-submit-message-panel-form').removeClass('hb-nephritis').addClass('hb-asbestos');
            $j('#hb-submit-message-panel-form').removeClass('disabled');
            $j('#twilio_message_id').prop("disabled", false);
            $j('#hb-submit-message-panel-form span.hb-push-button-text').html(reset_text);
            $j('#hb-submit-message-panel-form span.hb-push-button-icon').html("<i class='hb-moon-paper-plane'></i>");
        }

        $j(".msgskillid").val(this.id);
        var sms_id=this.id;
        //alert(sms_id);
        var sms_name=  $j(".sms_"+sms_id).attr('title');
        sms_arr = sms_name.split('-');
        console.log(sms_name);
        jQuery("#log_sms").empty();
        jQuery("#log_sms").append("to "+sms_arr[0]);
        if (!toggle) {
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 1, visibility: 'visible', scale: 1, ease:hb_gs.Power3.easeOutBouce});
            toggle = true;
        } else {
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 0, scale: 0.8, ease:hb_gs.Power3.easeOutBouce, onComplete:function(){
                $panel.css("visibility", "hidden");
                toggle = false;
            }
            });
        }

    });

    $j('#message-panel').click(function(e){
        e.stopPropagation();
    });

    $doc.click(function (e) {
        if ( toggle ) {
            $j('#sms-button').removeClass('active-c-button');
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 0, scale: 0.9, ease:hb_gs.Power1.easeOutBouce, onComplete:function(){
                $panel.css("visibility", "hidden");
                toggle = false;
            }
            });
            return false;
        }

    });
}

/* Contact Forms */
function onSuccessSmsSend(results){
    var success_text = $j('#success_text').val();
    $j('#hb-submit-message-panel-form i').attr('class','hb-moon-checkmark');
    $j('#hb-submit-message-panel-form').removeClass('hb-asbestos').addClass('hb-nephritis disabled');
    $j('#hb-submit-message-panel-form span.hb-push-button-text').html(success_text);
    $j('#hb-submit-message-panel-form span.hb-push-button-icon').html("<i class='hb-moon-checkmark-2'></i>");

    $j('#twilio_message_id').attr("disabled", "disabled");

}
function communication_sms(){
    var sent = false;
    var twilio_messageValidate = false;



    $j("#twilio_message_id").blur(function () {
        twilio_messageValidate = $j("#message-panel-form").validate().element("#twilio_message_id");
    });


    jQuery('#hb-submit-message-panel-form').click(function(e) {
        e.preventDefault();
        if (!sent){

            //if (jQuery('#hb_contact_subject_id').val()){
            //    alert("Sorry - bots are not allowed!");
            //    return false;
            //}

            if( twilio_messageValidate ) {
                //jQuery('#contact-name #contact-email, #contact-message').attr("disabled", true);

                var data = {};
                data.message_text = $j("#twilio_message_id").val();
                data.skillid = $j(".msgskillid").val();
                data.action = "sms_action";
                console.log(data);
                $j.post(ajaxurl, data, onSuccessSmsSend);
                //$j('#message-panel').hide();
                $j('#twilio_message_id').removeAttr('value');
                $j('#hb-submit-message-panel-form i').attr('class','hb-moon-spinner-8');


                sent = true;
                return;
            }
            else { jQuery("#message-panel-form").validate().form(); }
        }
    });
}
//SMS - End
//Call Start
function mini_call_form() {
    var toggle = false;

    $j('.call-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $panel = jQuery('#calling-panel');
        jQuery("#message-panel").hide();
        $panel.show();
        $j(".msgskillid").val(this.id);
        var call_id=this.id;
        var call_name=  $j(".call_"+call_id).attr('title');
        call_arr = call_name.split('-');
        jQuery("#log_call").empty();
        jQuery("#log_call").append(call_arr[0]);

        if (!toggle) {
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 1, visibility: 'visible', scale: 1, ease:hb_gs.Power3.easeOutBouce});
            toggle = true;
        } else {
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 0, scale: 0.8, ease:hb_gs.Power3.easeOutBouce, onComplete:function(){
                $panel.css("visibility", "hidden");
                toggle = false;
            }
            });
        }

    });

    /*$j('#calling-panel').click(function(e){
     e.stopPropagation();

     });*/

    $doc.click(function (e) {
        if ( toggle ) {
            $j('#call-button').removeClass('active-c-button');
            hb_gs.TweenLite.to($panel, 0.2, {opacity: 0, scale: 0.9, ease:hb_gs.Power1.easeOutBouce, onComplete:function(){
                $panel.css("visibility", "hidden");
                toggle = false;
            }
            });
            return false;
        }

    });
}

function communication_call(){
    var sent = false;

    jQuery('#hb-submit-startcalling').click(function(e) {
        jQuery(this).hide();
        jQuery("#hb-submit-endcalling").show();

        e.preventDefault();
        if (!sent) {
            var data = {};
            data.skillid = $j(".msgskillid").val();
            data.action = "call_action";
            console.log(data);
            $j.post(ajaxurl, data,function(data){
                console.log(data);
                var arr = [];

                for(var x in data){
                    arr.push(data[x]);
                }
                console.log(arr[1].token);
                call_twl(arr[1].token,arr[1].mobile);
            });


            sent = true;
            return;
        }

    });
}

function call_twl(token, number) {
    if(token!=''){
        //var sid = '#log-'+appSetting.skillid;
        ////alert(sid);
        ////jQuery(sid).text("You can call ");
        Twilio.Device.setup(token);
        Twilio.Device.ready(function (device) {
            //jQuery(sid).text("You can call ");
        });
    }
    // get the phone number to connect the call to
    params = {"PhoneNumber": number};
    Twilio.Device.connect(params);
}

function hangup() {
    Twilio.Device.disconnectAll();
}
//Call End
//Twilio Functionality - End
//Tokbox Functionality - Start

function communication_webrtc(){
    var publisher;
    var session;
    var sent = false;

    jQuery('.webrtc-btn').click(function(e) {
        e.preventDefault();
        if (!sent) {
            var data = {};
            data.skillid = this.id;
            data.action = "webrtc_action";
            $j.when(
                $j.post(ajaxurl, data,function(data){
                    console.log(data);
                })
            ).then(function(data){
                    var arr = [];

                    for(var x in data){
                        arr.push(data[x]);
                    }

                    jQuery('#open-video-dialog').modal('show');
                    console.log(arr);
                    session_connect = connectAV(arr[1].apiKey,arr[1].sessionId,arr[1].token);


                    //Open ScreenSharing onclick
                    $j('#start-screen-share').click(function(e) {
                        //Check Screen Sharing Capability of Browser
                        OT.checkScreenSharingCapability(function(response) {
                            //console.log(response);
                            if (!response.supported || response.extensionRegistered === false) {
                                alert('This browser does not support screen sharing.');
                            } else if (response.extensionInstalled === false) {
                                alert('Please install the screen sharing extension and load this page over HTTPS.');
                            } else {
                                // Screen sharing is available. Publish the screen.
                                // Create an element, but do not display it in the HTML DOM:
                                var screenContainerElement = document.createElement('div');
                                var screenSharingPublisher = OT.initPublisher(
                                    "stream-screen",
                                    { videoSource : 'screen' },
                                    function(error) {
                                        if (error) {
                                            alert('Something went wrong: ' + error.message);
                                        } else {
                                            session_connect.session.publish(
                                                screenSharingPublisher,
                                                function(error) {
                                                    if (error) {
                                                        alert('Something went wrong: ' + error.message);
                                                    }
                                                });
                                        }
                                    });
                            }
                        });
                    });

                    //alert(session_connect.unpublish(publisher));
                    $j('#close-call').click(function(e) {
                        //session_connect.session.unpublish(session_connect.publisher);
                        session_connect.session.disconnect();
                        $j('#open-video-dialog').modal('hide');
                    });

                });



            sent = true;
            return;
        }

    });
}



function advisor_webrtc() {
    //open video popup -Start

    var sent = false;

    //Connect to session and create stream - Start
    $j('#advisor-webrtc-button').click(function(e) {

        e.preventDefault();
        e.stopPropagation();
        var session_connect;

        if (!sent) {
            var data = {};
            data.action = "advisor_gen_token";
            $j.when(
                $j.post(ajaxurl, data,function(data){
                    console.log(data);

                })
            ).then(function(data){
                    var arr = [];

                    for(var x in data){
                        arr.push(data[x]);
                    }

                    jQuery('#open-video-dialog').modal('show');
                    //$j.jsPanel();

                    session_connect = connectAV(arr[1].apiKey, arr[1].sessionId, arr[1].token);
                    console.log(session_connect);
                    //alert(session_connect.unpublish(publisher));


                    //Open ScreenSharing onclick
                    $j('#start-screen-share').click(function(e) {
                        //Check Screen Sharing Capability of Browser
                        OT.checkScreenSharingCapability(function(response) {
                            //console.log(response);
                            if (!response.supported || response.extensionRegistered === false) {
                                alert('This browser does not support screen sharing.');
                            } else if (response.extensionInstalled === false) {
                                alert('Please install the screen sharing extension and load this page over HTTPS.');
                            } else {
                                // Screen sharing is available. Publish the screen.
                                // Create an element, but do not display it in the HTML DOM:
                                var screenContainerElement = document.createElement('div');
                                var screenSharingPublisher = OT.initPublisher(
                                    "stream-screen",
                                    { videoSource : 'screen', width: '100%', height: '100%' },
                                    function(error) {
                                        if (error) {
                                            alert('Something went wrong: ' + error.message);
                                        } else {
                                            session_connect.session.publish(
                                                screenSharingPublisher,
                                                function(error) {
                                                    if (error) {
                                                        alert('Something went wrong: ' + error.message);
                                                    }
                                                });
                                        }
                                    });
                            }
                        });
                    });

                    //Open ScreenSharing fullscreen
                    $j('#full-screen-share').click(function(e) {
                        var element = document.getElementById("stream-screen");
                        var fullScreenFunction = element.requestFullScreen || element.webkitRequestFullScreen || element.mozRequestFullScreen || element.msRequestFullScreen || element.oRequestFullScreen;

                        if (fullScreenFunction) {
                            fullScreenFunction.apply(element);
                        } else {
                            alert("don't know the prefix for this browser");
                        }
                    });

                    //Disconnect from session.
                    $j('#close-call').click(function(e) {
                        //session_connect.session.unpublish(session_connect.publisher);
                        console.log("publisher to unpublish: "+session_connect.publisher);
                        session_connect.session.disconnect();
                        $j('#open-video-dialog').modal('hide');
                    });
                });


            sent = true;
            return;
        }

    });

    //Connect to session and create stream - End


}
function connectAV(apiKey,sessionId,token) {
    //Initialise tokbox Session.
    var session = OT.initSession(apiKey,sessionId);
    var currentdate = new Date();

    //OT.registerScreenSharingExtension('chrome', 'bdoafbaoebhjdfojdbicgogfhgaoifcn'); //Anil Key ejcopkgfmileldoinegdebmhjiejpblo
    OT.registerScreenSharingExtension('chrome', 'ejcopkgfmileldoinegdebmhjiejpblo');
    //Initialise AV Publisher Object
    var publisherAvProperties = {insertMode: "append"};
    var publisherAV = OT.initPublisher('own-video',publisherAvProperties);



    //Connect to Session
    session.connect(apiKey, token);

    //Event Listener when connected to session
    session.addEventListener('sessionConnected', function(e){
        console.log("Session Connected");
        //Publish AV Stream
        session.publish(publisherAV, function(error) {
            if (error) {
                console.log(error);
            } else {
                console.log('Publishing a stream.');


                publisherAV.on('streamCreated', function (e) {
                    console.log('The publisher started streaming.');

                });
                publisherAV.on("streamDestroyed", function (e) {
                    console.log("The publisher stopped streaming. Reason: "+ e.reason);
                });
            }
        });
    });

    //Event Listner When Stream is detected in session
    session.addEventListener('streamCreated', function(e){
        for (var i = 0; i < e.streams.length; i++) {
            // Make sure we don't subscribe to ourself
            if (e.streams[i].connection.connectionId != session.connection.connectionId) {
                if (e.streams[i].videoType === 'screen') {
                    session.subscribe(e.streams[i], 'stream-screen');
                } else {
                    // Create the div to put the subscriber element in to
                    session.subscribe(e.streams[i], 'stream-video');
                    var now = new Date;
                    var starttime = currentdate.getFullYear() + "-"
                        + (currentdate.getMonth()+1)  + "-"
                        + currentdate.getDate() + " "
                        + currentdate.getHours() + ":"
                        + currentdate.getMinutes() + ":"
                        + currentdate.getSeconds();

                    console.log('sessionis: '+JSON.stringify(session, null, 4));
                    var data = {};
                    data.userid = session.connection.data;
                    data.start_time = starttime;
                    data.session_connection_id = session.connection.connectionId;
                    data.stream_connection_id = e.streams[i].connection.connectionId;
                    data.action = "web_call_start";
                    console.log('At the time of creation: '+ JSON.stringify(data, null, 4));
                    $j.post(ajaxurl, data,function(data){
                        console.log(data);
                    });
                }
            }
        }
    });

    session.addEventListener('streamDestroyed', function(e){
        var currentdate1 = new Date();
        var endtime = currentdate1.getFullYear() + "-"
            + (currentdate1.getMonth()+1)  + "-"
            + currentdate1.getDate() + " "
            + currentdate1.getHours() + ":"
            + currentdate1.getMinutes() + ":"
            + currentdate1.getSeconds();
        console.log(endtime);
        for (var i = 0; i < e.streams.length; i++) {
            var data = {};
            data.end_time = endtime;
            data.session_connection_id = session.connection.connectionId;
            data.stream_connection_id = e.streams[i].connection.connectionId;
            console.log("At the time of destroy: "+ JSON.stringify(data, null, 4));
            data.action = "web_call_end";
            $j.post(ajaxurl, data,function(data){
                console.log(data);
            });
        }
    });


    //Return Session and Publisher objects
    var return_param = {session:session,publisher:publisherAV};
    return return_param;
}


//Tokbox Functionality - End

//Communication Functionality - End



