function call_twl(token, number) {
   if(token!=''){
    var sid = '#log-'+appSetting.skillid;
    //alert(sid);
    //jQuery(sid).text("You can call ");
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

