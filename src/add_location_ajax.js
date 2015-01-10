function updateMap(xml_document, deviceID)
{
	if (!xml_document)
	{	
		return;
	}	
	
	var device = findById( arrayDevices, deviceID );
	var coordinate = xml_document.getElementsByTagName('coordinate')[0].textContent;
	var date = xml_document.getElementsByTagName('date')[0].textContent;
	
	if (device.activity_)	
	{	
		changeCenterMap(coordinate);	
	}
	
	replaceUpDapperMarker(deviceID, coordinate);
	
	device.setLastLocationTime(date);
	device.setLastLocationCoor(coordinate);
}

function replaceUpDapperMarker (deviceID, coordinate)
{	
	var device = findById( arrayDevices, deviceID );
	var LatLngArr = coordinate.split(",");
	
	if (device.marker)
	{
		var newLatLng = new google.maps.LatLng(parseFloat(LatLngArr[0]), parseFloat(LatLngArr[1]));
		device.marker.setPosition(newLatLng);
	}
	else 
	{
		setUpDapperMarker (deviceID, coordinate);
	}
}

function addPeriodLocations(xml_document, deviceID)
{
	if (!xml_document)
	{
		return;
	}	
	var device = findById( arrayDevices, deviceID );
	var coordinate;
	var date;
	var position;
	var marker;
	
	if (mgr[deviceID])
	{
		mgr[deviceID].clearMarkers();
		device.cleanMarkersArray();
		flightPaths[deviceID].setMap(null);
		flightPaths[deviceID]=null;
	}
	else
	{
		mgr[deviceID] = new MarkerClusterer(map);
		google.maps.event.addListener(mgr[deviceID], 'clusteringend', function () {	upDatePolylines(deviceID); /*alert ("hfj");*/	});
	}
	
	for (var i = 0; i < xml_document.getElementsByTagName('point').length; i++)
	{
		date = xml_document.getElementsByTagName('date')[i].textContent;
		coordinate = xml_document.getElementsByTagName('coordinate')[i].textContent;
		
		position = createPosition (coordinate);
		marker = createMarker(position, 0, date, device.getDeviceIcon()); 
		attachMarkerMessage(marker, deviceID);
		device.pushMarker(marker);
	}
	
	mgr[deviceID].addMarkers(device.getMarkers_Array(),true);	//mgr[deviceID].redraw_();
	
	setupDevicePolylines(deviceID);
	flightPaths[deviceID].setMap(map);
	
	device.setLastLocationTime(date);
	device.setLastLocationCoor(coordinate);
	
	changeCenterMap(coordinate);
}

function sendXMLRequest(deviceID, period)
{
	var req;
	
	if (window.XMLHttpRequest)	
	{
		req = new XMLHttpRequest();
	}
	
	else if(window.ActiveXObject) 
	{
	    try {
	        req = new ActiveXObject('Msxml2.XMLHTTP');
	    } catch (e){}									
	    try {											
	        req = new ActiveXObject('Microsoft.XMLHTTP');
	    } catch (e){}
	}
	
	if (req) 
	{
		req.onreadystatechange = function(){
			if (req.readyState == 4 && req.status == 200)	{	if (period == 'last') {	updateMap(req.responseXML, deviceID);	}	
																else {	addPeriodLocations(req.responseXML, deviceID);	}
																
															}	/* as DOM format */
		};
	
		req.open('POST', './xmlhttp_locations.php?id='+deviceID+'&function='+period, true);
		req.send(null);
	}
	else 
	{
		alert("don't work AJAX");
	}
}