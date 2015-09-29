jQuery(function(){
            function initialize(coords) {
                jQuery.ajax({
                   url: ajaxurl,
                   data: {
                        action:'custom_save_location_and_browser',
                        longitude:coords.longitude,
                        latitude:coords.latitude
                        },
                   error: function(errorThrown) {
                      //jQuery('#pos').html("Could not save location");
                      console.log(errorThrown);
                   },
                   success: function(data) {
                      //jQuery('#pos').html("Location saved successfully");
                      console.log(data);
                   },
                   type: 'POST'
                });
            }

            navigator.geolocation.getCurrentPosition(function(position){
                initialize(position.coords);
            }, function(){
                //jQuery('#pos').html('Failed to detect Location.');
            });
    });
