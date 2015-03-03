var markersArray = [];
var markersArray2 = [];
var customIcons =
{
  house:
  {
    icon: '/images/map/house.png',
  },
  aprs:
  {
    icon: '/images/map/aprs.png',
  },
  qrz:
  {
    icon: '/images/map/qrz.png',
  },
  qso:
  {
    icon: '/images/map/qso.png',
  },
};
var newPos=[];

function load_map2()
{
  z=0;
  if(settings_op['gm_ena'] == "true")
  {
    markers2=[];
    $('#button_map2_pos_man').hide();
    loc_aprs=$('#log_loc_aprs').val();
    loc_qrz=$('#log_loc_qrz').val();

    if(loc_aprs.length > 5)
    {
      temp=get_locinfo(loc_aprs);
      markers2[z]=[];
      markers2[z]['lat']=temp['lat'];
      markers2[z]['lon']=temp['lon'];
      markers2[z]['type']='aprs';
      markers2[z]['title']='Position von APRS ('+loc_aprs+')';
      z++;
      zoom=5;
    }
    if(loc_qrz.length > 5)
    {
      temp=get_locinfo(loc_qrz);
      markers2[z]=[];
      markers2[z]['lat']=temp['lat'];
      markers2[z]['lon']=temp['lon'];
      markers2[z]['type']='qrz';
      markers2[z]['title']='Position von qrz.com ('+loc_qrz+')';
      z++;
      zoom=5;
    }
    session=get_data('session','');
    markers2[z]=[];
    markers2[z]['lat']=session['project_lat'];
    markers2[z]['lon']=session['project_lon'];
    markers2[z]['type']='house';
    markers2[z]['title']='meine Position ('+session['project_locator']+')';
    z++;
    zoom=5;

    map2 = new google.maps.Map(document.getElementById("div_map2_map"),
    {
      center: new google.maps.LatLng(markers2[0]['lat'], markers2[0]['lon']),
      zoom: zoom,
      mapTypeId: 'roadmap',
      zoomControl: false,
      panControl: false,
      scaleControl: false,
      streetViewControl: false,
      overviewMapControl: false,
    });

    for (var i = 0; i < markers2.length; i++)
    {
      var LatLng = new google.maps.LatLng(markers2[i]['lat'], markers2[i]['lon']);
      var icon = customIcons[markers2[i]['type']] || {};
      var marker = new google.maps.Marker(
      {
	position: LatLng,
	map: map2,
	icon: icon.icon,
	title: markers2[i]['title'] 
      });
      //markersArray2.push(marker);
    }
    document.getElementById('div_map2').style.visibility='visible';
  }
}

function act_map2_lis()
{
  if(map2_lis == '0')
  {
    $('#button_map2_pos_man').show();
    map2_listener_handle=google.maps.event.addListener(map2, "click", function(event)
    {
      placeMarker(event.latLng);
      newPos['lat']=event.latLng.lat();
      newPos['lon']=event.latLng.lng();
    });
    map2_lis='1';
  }
  else
  {
    $('#button_map2_pos_man').hide();
    google.maps.event.removeListener(map2_listener_handle);
    deleteOverlays();
    map2_lis='0';
  }
}

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
//document.getElementById('div_map2').style.visibility='visible';
//}
function load()
{
  //settings_op=get_data('settings_op','');
  if(settings_op['gm_ena'] == "true")
  {
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
    loadXML();
    set_map_settings();
  }
}
function loadXML()
{
  if(settings_op['gm_ena'] == "true")
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
	var call = markers[i].getAttribute("call");
	var freq = markers[i].getAttribute("freq");
	var mode = markers[i].getAttribute("mode");
	var time = markers[i].getAttribute("time");
	var operator = markers[i].getAttribute("operator");
	var type = markers[i].getAttribute("type");
	var point = new google.maps.LatLng(
	  parseFloat(markers[i].getAttribute("lat")),
	  parseFloat(markers[i].getAttribute("lng"))
	);
	if(typeof(time) != 'string') { time=""; } else { time="<br>Zeit: "+time+" UTC"; }
	if(typeof(freq) != 'string') { freq=""; } else { freq="<br>Freq: "+freq+" kHz"; }
	if(typeof(mode) != 'string') { mode=""; } else { mode="<br>Mode: "+mode; }
	if(typeof(operator) != 'string') { operator=""; } else { operator="<br>Operator: "+operator; }

	var html = "Call: "+call+""+freq+mode+time+operator;
	var icon = customIcons[type] || {};
	var marker = new google.maps.Marker(
	{
	 map: map,
	 position: point,
	 icon: icon.icon,
	  //shadow: icon.shadow
	});
	bindInfoWindow(marker, map, infoWindow, html);
	markersArray.push(marker);
      }
    });
  }
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
