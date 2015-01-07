var markersArray = [];         
var markersArray2 = [];          

var customIcons = 

{
  /*
  shack: 
  {
    icon: '/images/d22_house.png',
    //icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
    //shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
  },
  aprs: 
  {
    icon: '/images/d22_car.png',
    //icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
    //shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
  }
  */
};
var newPos=[];

function load_map2() 
{
  loc=$('#log_loc').val();
  if(loc.length < 5)
  {
    session=get_data('session','');
    marker=[];
    marker['lat']=session['project_lat'];
    marker['lon']=session['project_lon'];
    zoom=5;
  }
  else
  {
    marker=get_locinfo(loc);
    zoom=10;
  }
  newPos['lat']=marker['lat'];
  newPos['lon']=marker['lon'];
  var LatLng = new google.maps.LatLng(marker['lat'], marker['lon']);
  map2 = new google.maps.Map(document.getElementById("div_map2_map"), 
  {
    center: new google.maps.LatLng(marker['lat'], marker['lon']),
    zoom: zoom,
    mapTypeId: 'roadmap',
    zoomControl: false,
    panControl: false,
    scaleControl: false,
    streetViewControl: false,
    overviewMapControl: false,
  });
  var marker = new google.maps.Marker(
  {
    position: LatLng,
    map: map2,
    title: 'Hello World!'
  });
  markersArray2.push(marker);

  google.maps.event.addListener(map2, "click", function(event)
  {
    // place a marker
    placeMarker(event.latLng);
    //alert('bla');
    // display the lat/lng in your form's lat/lng fields
    //document.getElementById("latFld").value = event.latLng.lat();
    //document.getElementById("lngFld").value = event.latLng.lng();
    newPos['lat']=event.latLng.lat();
    newPos['lon']=event.latLng.lng();
  });
 
  function placeMarker(location) 
  {
    // first remove all markers if there are any
    deleteOverlays();

    var marker = new google.maps.Marker(
    {
      position: location, 
      map: map2
    });

    // add marker in markers array
    markersArray2.push(marker);
    //map.setCenter(location);
  }

  // Deletes all markers in the array by removing references to them
  function deleteOverlays() 
  {
    if (markersArray2) 
    {
      for (i in markersArray2) 
      {
	markersArray2[i].setMap(null);
      }
      markersArray2.length = 0;
    }
  }
  document.getElementById('div_map2').style.visibility='visible';
}

function load() 
{
  session=get_data('session','');
  map = new google.maps.Map(document.getElementById("div_map_map"), 
  {
    center: new google.maps.LatLng(session['project_lat'], session['project_lon']),
    zoom: 4,
    mapTypeId: 'roadmap',
    //disableDefaultUI: true,
    zoomControl: false,
    panControl: false,
    scaleControl: false,
    streetViewControl: false,
    overviewMapControl: false,

  });
  infoWindow = new google.maps.InfoWindow;
}

function loadXML()
{
  if (markersArray) 
  {
    for (i=0; i < markersArray.length; i++) 
    {
      markersArray[i].setMap(null);
    }
    markersArray.length = 0;
  }	
  downloadUrl("/map_data.php", function(data) 
  {
    var xml = data.responseXML;
    markers = xml.documentElement.getElementsByTagName("marker");
    for (var i = 0; i < markers.length; i++) 
    {
      var name = markers[i].getAttribute("name");
      var type = markers[i].getAttribute("type");
      var point = new google.maps.LatLng(
	parseFloat(markers[i].getAttribute("lat")),
        parseFloat(markers[i].getAttribute("lng"))
      );
      var html = "<b>" + name;
      var icon = customIcons[type] || {};
      var marker = new google.maps.Marker(
      {
	map: map,
        position: point,
        icon: icon.icon,
        shadow: icon.shadow
      });
      markersArray.push(marker);
      bindInfoWindow(marker, map, infoWindow, html);
    }
  });
}

function bindInfoWindow(marker, map, infoWindow, html) 
{
  google.maps.event.addListener(marker, 'click', function() 
  {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
}

function downloadUrl(url, callback) 
{
  var request = window.ActiveXObject ?
  new ActiveXObject('Microsoft.XMLHTTP') :
  new XMLHttpRequest;

  request.onreadystatechange = function() 
  {
    if (request.readyState == 4) 
    {
      request.onreadystatechange = doNothing;
      callback(request, request.status);
    }
  };
  request.open('GET', url, true);
  request.send(null);
}

function doNothing() 
{
}
