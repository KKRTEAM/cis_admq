jQuery(document).ready(function(){
    
    jQuery("#mobile_number-297").attr('placeholder','+1 999 999 9999');
    jQuery("#mobile_number-307").attr('placeholder','+1 999 999 9999');
    jQuery("#mobile_number-250").attr('placeholder','+1 999 999 9999');
    jQuery("#mobile_number-374").attr('placeholder','+1 999 999 9999');
jQuery("#mobile_number-307,#mobile_number-297,#mobile_number-250,#mobile_number-374").intlTelInput({
        nationalMode: false,
        utilsScript: "http://arianadigital.kr.cisinlive.com/wp-content/themes/HighendWP/scripts/utils.js"
      });

function initialize() {
 
   if(jQuery( "#address-307" ).length==1){
    addr="address-307";
    autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address-307'));
   }else if(jQuery( "#address-250" ).length==1){
    addr="address-250";
    autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address-250'));
   }else if(jQuery( "#address-374" ).length==1){
    addr="address-374";
    autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address-374'));
   }
 //  alert(addr);
 //if(jQuery( "#address-250" ).length==1){
 //   autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address-250'));
 //}else{
 //  autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('address-297'));
 //}
   //autocomplete4 = new google.maps.places.Autocomplete(document.getElementById('address-250'));
  // autocomplete3 = new google.maps.places.Autocomplete(document.getElementById('address-250'));
 //  alert(addr);
 var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 13
  };
  var map = new google.maps.Map(mapOptions);

if (jQuery( "#address-297" ).length==1) {
  var input = /** @type {HTMLInputElement} */(
      document.getElementById("address-297"));
}else{
    var input = /** @type {HTMLInputElement} */(
      document.getElementById(addr));
}
  
  
  //var options = {
  //         componentRestrictions: { country: "in" }
  //      };

  var types = document.getElementById('type-selector');
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      window.alert("Autocomplete's returned place contains no geometry");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    //marker.setIcon(/** @type {google.maps.Icon} */({
    //  url: place.icon,
    //  size: new google.maps.Size(71, 71),
    //  origin: new google.maps.Point(0, 0),
    //  anchor: new google.maps.Point(17, 34),
    //  scaledSize: new google.maps.Size(35, 35)
    //}));
    //marker.setPosition(place.geometry.location);
    //marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    var country, postal_code, locality, sublocality,state;
for (i = 0; i < place.address_components.length; ++i) {
    var component = place.address_components[i];
    if (!sublocality && component.types.indexOf("sublocality") > -1)
        sublocality = component.long_name;
    else if (!locality && component.types.indexOf("locality") > -1)
        locality = component.long_name;
    else if (!postal_code && component.types.indexOf("postal_code") > -1)
        postal_code = component.long_name;
    else if (!country && component.types.indexOf("country") > -1)
        country = component.long_name;
        else if (!state && component.types.indexOf("administrative_area_level_1") > -1)
        state = component.long_name;
}


console.log(place.address_components);
console.log(component);
console.log(locality);
console.log(state);
//alert(jQuery( "#address-250" ).length);
    if(jQuery( "#address-250" ).length==1){
        jQuery("#zip_code-250").val(postal_code);
        jQuery("#city-250").val(locality);
        jQuery("#state-250").val(state);
        jQuery("#countries-250").val(country);
       }
       
       if(jQuery( "#address-374" ).length==1){
        jQuery("#zip_code-374").val(postal_code);
        jQuery("#city-374").val(locality);
        jQuery("#state-374").val(state);
        jQuery("#countries-374").val(country);
       }
       
    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });

  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    google.maps.event.addDomListener(radioButton, 'click', function() {
      autocomplete.setTypes(types);
    });
  }

  setupClickListener('changetype-all', []);
  setupClickListener('changetype-address', ['address']);
  setupClickListener('changetype-establishment', ['establishment']);
  setupClickListener('changetype-geocode', ['geocode']);
}
google.maps.event.addDomListener(window, 'load', initialize);
});