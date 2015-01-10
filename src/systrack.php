<?php 

define ('SYSPATH_BASE','/home/easywhere/www/systrack/src');     //      /home/easywhere
define ('RPCPATH_BASE','/home/easywhere-rpc/www/');
define ( 'DS', DIRECTORY_SEPARATOR );

/****	includes	****/
	require_once SYSPATH_BASE.DS.'class/class_db.php';
	require_once SYSPATH_BASE.DS.'class/class_device.php';

	$icons_path = SYSPATH_BASE.DS.'images/icons';
	$markers_path = SYSPATH_BASE.DS.'images/markers';

/****	getting user id 	****/
	//TODO::import userID from CMS
//test user
	$userID = 17;

/****	getting  config info from config.ini file	****/	
	$ini_array = parse_ini_file("./config.ini", true);
		
	$db_host = $ini_array['database']['host'];
	$db_name = $ini_array['database']['name'];
	$db_user = $ini_array['database']['user'];
	$db_pass = $ini_array['database']['pass'];

	$deviceTable 		= $ini_array['table']['device'];
	$locationTable 		= $ini_array['table']['location'];
	$eventTable 		= $ini_array['table']['event'];
	$geoTable 			= $ini_array['table']['geozone'];
	
/****	trying to connect to db	****/
	$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
	if (!$db->IsConnected())
		exit("Error connect to DB!");
	
	
//	$sql = "SELECT `user_timezone` FROM `user` WHERE `user_ID` = '2'";
//	$userTime = new DateTime(null, new DateTimeZone($db->query($sql, 0)));
//	echo $userTime->format('Y-m-d H:i:sP') . "\n";

//TODO::choise language, time_zone
	
	/*???	echo @$_COOKIE[''];*/
	if (isset($_GET['lang'])) 
		$langMap = $_GET['lang'];
	else
		$langMap = $ini_array['map']['defaultLanguage'];


/****	getting device ID 	****/
	$sql = "SELECT `device_ID` FROM ".$deviceTable." WHERE `device_userID` = '".$userID."'";
	$tmp = $db->query($sql, 0);
	
/****	creating arrays with device ID's and device objects 	****/	
	$deviceObjects = array();
	
	if(is_array($tmp))
	{
		foreach ($tmp as $obj)
		{
			$deviceObjects[] = new Device($db, $obj->device_ID);
		}
	}
   	else if (count($tmp) == 1)
	{
		$deviceObjects[] = new Device($db, $tmp);
	}
	else
	{
		//TODO:: no devices !!!
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" >
<!-- turn off cache, for debugging -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="Fri, 01 Jan 1990 00:00:00 GMT"/>

<link href="./css/style.css" rel="stylesheet" type="text/css">
<link type="text/css" href="./bootstrap/css/bootstrap.css" rel="stylesheet">

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&language=<?php echo $langMap;?>"></script>

<script type="text/javascript">
	var script = '<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer';
    if (document.location.search.indexOf('compiled') !== -1) 
	{
		script += '_packed';
    }
    script += '.js"><' + '/script>';
    document.write(script);
</script>

<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>

<script type="text/javascript" src="./bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="./map_api.js"></script>
<script type="text/javascript" src="./devicemanager.js"></script>
<script type="text/javascript" src="./add_location_ajax.js"></script>
<script type="text/javascript" src="./add_settings_list_ajax.js"></script>

<script type="text/javascript">

var icons_path = <?php echo '"'.$icons_path.'"'; ?>;
var markers_path = <?php echo '"'.$markers_path.'"'; ?>;
var userID = <?php echo '"'.$userID.'"'; ?>;

<?php
	/****	array of device objects	****/
	echo	"var device;
			 var arrayDevices = [];
			 var director = new Director;			 
			";
	for ($n = 0; $n < count($deviceObjects); $n++)
	{/**** create device with pattern Director ****/	
		$device_type = ($deviceObjects[$n]->getDeviceType()== 1) ? 'CarHideTracker' : 'PetTracker'; 
		echo	"var tracker = new ".$device_type."('".$deviceObjects[$n]->getDeviceID()."', 
													'".$deviceObjects[$n]->getDeviceAlias()."', 
													'".$deviceObjects[$n]->getDeviceIcon()."', 
													'".$deviceObjects[$n]->getDeviceLastLocationTime()."', 
													'".$deviceObjects[$n]->getDeviceLastLocation()."');
				 director.setDeviceBuilder(tracker);
				 director.constructDevice();
				 device = director.getDevice();
				 device.last_location_temp_ =  '".$deviceObjects[$n]->getDeviceLastLocationSpeed()."';
				 device.last_location_speed_ = '".$deviceObjects[$n]->getDeviceLastLocationAltitude()."';
				 device.last_location_stat_	 = '".$deviceObjects[$n]->getDeviceLastLocationSatellites()."';
				
				 arrayDevices.push(device);
				";                     
	}
?>

var map = null;					/* Map */

var mgr = new Array();			/* Marker Manager */
var flightPaths = new Array();	/* Polylines */


var SettingAccel;
var SettingColor;
var id_setting_update;

function setupMap() 
{// map options and map creation
	var myOptions = {
			zoom: 10,
			center: new google.maps.LatLng(55.75,37.60),
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'), myOptions);
	isDemoModeNow ();
	
	for (var i = 0; i < arrayDevices.length; i++)		
	{	
		onClickTurnButton( arrayDevices[i].getID() );
	}
}

google.maps.event.addDomListener(window, 'load', setupMap);

function upDatePolylines(deviceID)
{
	if (flightPaths[deviceID])
	{
		flightPaths[deviceID].setMap(null);
		flightPaths[deviceID]=null;
	}
	
	var batch = [];
	var position = 0;
	var markers = mgr[deviceID].getMarkers();
	var clusters = mgr[deviceID].clusters_;
	
	for (var j = 0; j < markers.length; j++)
	{	
		position = markers[j].getPosition();
		for (var k = 0; k < clusters.length; k++)	
		{	
			if (clusters[k].isMarkerInClusterBounds(markers[j]))	
			{
				position = clusters[k].getCenter(); 
				break;
			}	
		}
		batch.push(new google.maps.LatLng(position.lat(), position.lng()));
	}

	flightPaths[deviceID] = new google.maps.Polyline({			
		path: batch,
		strokeColor: "#FF0000",
		strokeOpacity: 1.0,
		strokeWeight: 2
	  });
	  
	flightPaths[deviceID].setMap(map);
}

function setOpacity(id, value)
{	//just change opacity
	document.getElementById(id).style.opacity = value;
}

function turnOnDeviceActivity(deviceID)
{
	var device = findById( arrayDevices, deviceID );
	
	for (var i = 0; i < arrayDevices.length; i++)
	{	
		arrayDevices[i].activity_ = false;
		setOpacity('traker_alias'+arrayDevices[i].getID(), 0.5);
	}
	
	changeCenterMap(device.getLastLocationCoor());
	
	device.activity_ = true;
	setOpacity('traker_alias'+deviceID, 1);
	
	if (document.getElementById("toggle_mode").src.indexOf("history_mode.png") > 0) 
	{
		setOpacity('period_trackbar_div', 1);	
	}
}

function turnOffDeviceActivity(deviceID)
{
	var device = findById( arrayDevices, deviceID );
	
	device.activity_ = false;
	setOpacity('traker_alias'+deviceID, 0.5);
	
	if (document.getElementById("toggle_mode").src.indexOf("history_mode.png") > 0) 
	{
		setOpacity('period_trackbar_div', 0);	
	}
}

function onClickTurnButton(deviceID)
{	// click on device light action
	var device = findById( arrayDevices, deviceID );
	if (device.show_)
	{
		if(document.getElementById("toggle_mode").src.indexOf("history_mode.png") > 0)
		{	
			device.show_ = false;
			setOpacity('img'+deviceID, 0.5);
			turnOffDeviceActivity(deviceID);
		}
		else
		{
			onClickActivityButton(deviceID);
		}
		
	}
	else
	{
		device.show_ = true;
		setOpacity('img'+deviceID, 1);
		turnOnDeviceActivity(deviceID);
	}

	(document.getElementById("toggle_mode").src.indexOf("history_mode.png") > 0) ? staticAction(deviceID):dynamicAction(deviceID);
}

function onClickActivityButton(deviceID)
{	// click on device alias action
	var device = findById( arrayDevices, deviceID );
	//choose way of action
	if (device.activity_ && device.show_)
	{
		turnOffDeviceActivity(deviceID);
	}
	else if (!device.activity_ && device.show_)
	{
		turnOnDeviceActivity(deviceID);
	}
	else if (!device.activity_ && !device.show_)
	{
		onClickTurnButton(deviceID);
	}
	else
	{
		alert ("no way - what happens?");
	}
}

function staticAction(deviceID)
{
	var device = findById( arrayDevices, deviceID );

	if (mgr[deviceID])
	{	/* device is active */
		mgr[deviceID].clearMarkers();
		device.cleanMarkersArray();
		google.maps.event.clearListeners(mgr[deviceID], 'clusteringend');
		delete mgr[deviceID];
		
		flightPaths[deviceID].setMap(null);
		flightPaths[deviceID]=null;
	}
	
	else
	{	/* device is inactive */
		onChangePeriodTrackBar();
	}
}

function dynamicAction(deviceID)
{
	var device = findById( arrayDevices, deviceID );

	if (device.show_ )
	{		
		//show last position by marker
		setUpDapperMarker (deviceID, device.getLastLocationCoor());
		//track him
		device.time_intreval_ = setInterval("sendXMLRequest(" + deviceID + ", 'last')", 5000);
	}
	
	else
	{	
		clearInterval(device.time_intreval_); 

		var current_marker = device.marker;
		current_marker.setMap(null);
		current_marker = null;
	} 
}

function setUpDapperMarker (deviceID, coordinate)
{		
	var device = findById( arrayDevices, deviceID );
	var LatLngArr = coordinate.split(",");
	
	device.marker = new google.maps.Marker({
   		position: new google.maps.LatLng(parseFloat(LatLngArr[0]), parseFloat(LatLngArr[1])),
        map: map,
        icon: markers_path+device.getDeviceIcon(deviceID)+".png",
		//animation: google.maps.Animation.BOUNCE,
		//draggable: true,
        title: 'dupper device marker'
        });
}

function onChangeTrackBar()
{
	document.getElementById('trackBarValue').innerHTML = document.getElementById('trackbar').value;
}

function onChangePeriodTrackBar()
{
	var track_value = document.getElementById('period_trackbar').value;
	var period = '';
	
	if (track_value == 0) {period = 'hour';}
	if (track_value == 1) {period = 'day';}
	if (track_value == 2) {period = 'month';}
	if (track_value == 3) {period = 'year';}

	for (var i = 0; i < arrayDevices.length; i++)		
	{	
		if (arrayDevices[i].activity_)	
		{	
			sendXMLRequest(arrayDevices[i].getID(), period); 
			arrayDevices[i].setHistoryValue( track_value );
			break;
		}
	}
}

function onClickSettingsIcon(color_num,deviceID)
{
	var color = ["green", "blue", "yellow", "orange"];
	document.getElementById('SettingsIcon').src =icons_path+color[color_num]+'_icon_off.png';
	SettingColor = color[color_num];
}

function onMouseOverDeviceForm (deviceID)
{
	var device = findById( arrayDevices, deviceID );
	var tooltip_content = "Current state of device - " + deviceID + " temp : " + device.last_location_temp_ + "C; speed : " + device.last_location_speed_ + "km/h; accel stat: " + device.last_location_stat_ + ";";
	
	$('div[rel=tooltip'+deviceID+']').tooltip({trigger: 'manual', animation: false})
									 .attr('data-original-title', tooltip_content)
									 .tooltip('fixTitle')
									 .tooltip('show');
}

function onMouseOutDeviceForm (deviceID)
{
	$('div[rel=tooltip'+deviceID+']').tooltip('hide')
									 .tooltip('destroy');;
}

function isDemoModeNow ()
{	//TODO: demo user id is global, not = 17
	if (userID != 17)
	{	
		document.getElementById('demo_arert').style.display = 'none';
	}
}
</script>

</head>


<body bgcolor="#D6D6D6" leftmargin="0" topmargin="0"  marginwidth="0" marginheight="0">

	<div id="container">

		<div id="map"></div>
		
		<div id="demo_arert" style="background-color: #333333; border-color: #333333;position:absolute; margin: 0% 25% 0 25%; width:50%; opacity:0.7;" class="alert alert-block alert-error fade in">
            <h4 class="alert-heading" style="color: rgb(245, 237, 237);">Oh snap! You in demo mode!</h4>
            <p style="color: rgb(245, 237, 237);">Choose way of action - log in or register</p>
            <p>
              <a class="btn btn-danger" href="#">Register</a> <a class="btn" href="#">log in</a>
            </p>
        </div>
				
		<div id="period_trackbar_div">
			<input type="range" id="period_trackbar" onchange="onChangePeriodTrackBar()" min="0" max="3" value="0" step="1">
		</div>
		
		<div id="map_bar">
			
			<img id="plus" onclick="map.setZoom(map.getZoom()+1)" src="./images/plus.png">
			
			<img id="minus" onclick="map.setZoom(map.getZoom()-1)" src="./images/minus.png">
			
			<img id="toggle_mode" onclick="toggleModeFunc()" src="./images/online_mode.png">
			
		</div>
		
		<div id="bottom_bar" >
			<?php 
				/* show icons */
				for ($i = 0; $i < count($deviceObjects); $i++)
				{
					echo '
						<div class="device" rel="tooltip'.$deviceObjects[$i]->getDeviceID().'" onmouseover="onMouseOverDeviceForm('.$deviceObjects[$i]->getDeviceID().')" onmouseout="onMouseOutDeviceForm('.$deviceObjects[$i]->getDeviceID().')">
							
							<div id="device_light"  onclick="onClickTurnButton('.$deviceObjects[$i]->getDeviceID().')">
								<img id="img'.$deviceObjects[$i]->getDeviceID().'" style="opacity:0.5" name="img'.$deviceObjects[$i]->getDeviceID().'" src="'.$icons_path.''.$deviceObjects[$i]->getDeviceIcon().'_icon_off.png">
							</div>
						
							<div id="tracker_name" onclick="onClickActivityButton('.$deviceObjects[$i]->getDeviceID().')">
								<a id="traker_alias'.$deviceObjects[$i]->getDeviceID().'" style="display: inline-block; color: #ffffff; opacity:0.5;">'.$deviceObjects[$i]->getDeviceAlias().'</a>
							</div>
						
							<div id="device_option_button" onclick="sendXMLSettingsRequest('.$deviceObjects[$i]->getDeviceID().')">
								<a href="#myModal" data-toggle="modal" style="display: inline-block; outline:none;"><img width="37px" height="37px" name="options" src="./images/options.png" /></a>
							</div>

							<div id="separator"name="separator"><img src="./images/separator.png"></div>
						
						</div>';
				}
			?>
		</div>

	</div>

	<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel">Settings</h3>
		</div>
		 
			<div id="dinamic_container" class="modal-body">

			</div>			

		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button onclick="sendXMLUpdateRequest()" class="btn btn-primary">Save changes</button>
		</div>

	</div>
	
	<script type="text/javascript">  
		$('#myModal').modal({
			show:false
		});
		
		$('#myRadioButton').button();
	</script>  

	
</body>
</html>