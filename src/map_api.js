/****	Markers	****/
function createPosition (lat_lon)
{
	var lat_lon_arr = lat_lon.split(',');
	position = new google.maps.LatLng(parseFloat(lat_lon_arr[0]), parseFloat(lat_lon_arr[1]));
	
	return position;
}

function createMarker(position, speed, time, marker_image)
{
	var marker = new google.maps.Marker({	position: 	position,
								map:		map,
								icon: markers_path+"marker_image_"+marker_image+".png",
								my_speed: 	speed,
								my_time: 	time,
								title: 		'i am a static'
							});
	return marker;
}

function attachMarkerMessage(marker, deviceID)
{	//var message = "<div>hi, my id is "+deviceID+", my speed is "+marker.my_speed+",</br>my time is "+marker.my_time+"</div>";
	var boxText = document.createElement("div");
	boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: white; padding: 5px;";
	boxText.innerHTML = "hi, my id is "+deviceID+", my speed is "+marker.my_speed+",</br>my time is "+marker.my_time;
	
	var infowindow = new InfoBox({
			content: boxText,
			disableAutoPan: false,
			maxWidth: 0,
			pixelOffset: new google.maps.Size(-140, 0),
			zIndex: null,
			boxStyle: { 
			  background: "url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat",
			  opacity: 0.75,
			  width: "280px"
			 },
			closeBoxMargin: "10px 2px 2px 2px",
			closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
			infoBoxClearance: new google.maps.Size(1, 1),
			isHidden: false,
			pane: "floatPane",
			enableEventPropagation: false
		});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}
/****	PolyLines	****/
function setupDevicePolylines(deviceID)
{
		var batch = [];
		var markers = mgr[deviceID].getMarkers();
		
		for (var j = 0; j < markers.length; j++)
		{	
			position = markers[j].position;
			batch.push(new google.maps.LatLng(position.lat(), position.lng()));
		}

		flightPaths[deviceID] = new google.maps.Polyline({			
			path: batch,
			strokeColor: "#FF0000",
			strokeOpacity: 1.0,
			strokeWeight: 2
		  });
}

function changeCenterMap(Location)
{
	if ((Location) && (Location != null))
	{
		var arr = Location.split(",");
		map.panTo(new google.maps.LatLng(parseFloat(arr[0]), parseFloat(arr[1])));
	}
	else
	{
		/* oops, no location, yet */
	}
}
/****	Icons	****/
function getDeviceIcon(deviceID)
{
	var device = findById( arrayDevices, deviceID );
	var icon = device.getDeviceIcon();

	var iconImage = new google.maps.MarkerImage(icons_path+icon+'.png',
		new google.maps.Size(48, 48)
	);

	return iconImage;
}
/****	General Buttons****/
function toggleModeFunc()
{	
	if (document.getElementById("toggle_mode").src.indexOf("online_mode.png") > 0)
	{
		document.getElementById("toggle_mode").src = "http://www.j25.easywhere.ru/systrack/src/images/history_mode.png";
		for (var i = 0; i < arrayDevices.length; i++)		
		{	
			if (arrayDevices[i].activity_)	{ var active_device_id = arrayDevices[i].getID(); }
			if (arrayDevices[i].show_)	{	onClickTurnButton( arrayDevices[i].getID() );	}
		}
		if (active_device_id)	{	onClickTurnButton(active_device_id);	}
	}
	else
	{
		for (var i = 0; i < arrayDevices.length; i++)		
		{	
			if (arrayDevices[i].show_)	{	onClickTurnButton( arrayDevices[i].getID() );	}
		}
		document.getElementById("toggle_mode").src = "http://www.j25.easywhere.ru/systrack/src/images/online_mode.png";
		for (var i = 0; i < arrayDevices.length; i++)		
		{
			onClickTurnButton( arrayDevices[i].getID() );
		}
	}
}