var markersArray = [];          
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
function load() 
{
  session=get_data('session','')
  map = new google.maps.Map(document.getElementById("div_map"), 
  {
    center: new google.maps.LatLng(session['project_lat'], session['project_lon']),
    zoom: 4,
    mapTypeId: 'roadmap'
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
