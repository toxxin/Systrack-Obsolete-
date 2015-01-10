function bild_settings_list(xml_document, deviceID)
{
	SettingAccel=xml_document.getElementsByTagName('device_accel_stat')[0].textContent;
	id_setting_update=deviceID;
	
	if (xml_document)
	{	
		var status = new Array();
		
		var current_doc = document.getElementById('dinamic_container');
		current_doc.innerHTML = '';
		current_doc.innerHTML +='<div style="margin: 15px;"><div style="display: inline-block;">Device Alias</div><input type="text"  id="deviceAliasValue" value="'+xml_document.getElementsByTagName('device_alias')[0].textContent+'" class="span3" style="width:70px; margin: 0 auto; float:right;" data-provide="typeahead"></div>';
		
		if (xml_document.getElementsByTagName('device_accel_stat')[0].textContent=='1') {status[0]='btn active'; status[1]='btn';}
		else {status[0]='btn'; status[1]='btn active';}
		
		current_doc.innerHTML +='<div style="margin: 15px;"><div style="display: inline-block;">Device Accelerometr Status</div><div style="display: inline-block; float:right;" id="myRadioButton" class="btn-group" data-toggle="buttons-radio"><button type="button"  onclick="onClickRadioAccel(1)" class="'+status[0]+'">on</button><button type="button" onclick="onClickRadioAccel(0)" class="'+status[1]+'">off</button></div></div>';
 	 	 
		current_doc.innerHTML +='<div style="margin: 15px;"><div style="display: inline-block;">Device Time Interval(0 - const)</div> Value: <span id="trackBarValue">'+xml_document.getElementsByTagName('device_time_interval')[0].textContent+'</span><input type="range" id="trackbar" onchange="onChangeTrackBar()" style="width:70px; margin: 0 auto; float:right;" min="0" max="50" step="5" value="'+xml_document.getElementsByTagName('device_time_interval')[0].textContent+'" /></div>';
		
		current_doc.innerHTML +='<div style="margin: 15px;"><div style="display: inline-block;">Device Icon</div><div style="display: inline-block; float:right;"><ul class="nav nav-pills"><li class="dropdown"><a class="dropdown-toggle" style="outline:none; padding:0 0 0 0; margin:0 0 0 0;" data-toggle="dropdown" href="#"><img id="SettingsIcon" style="height:30px; width:30px;" src="'+icons_path+xml_document.getElementsByTagName('device_icon')[0].textContent+'_icon_off.png"></a><ul class="dropdown-menu"  role="menu" style="top:-15px; left:-210px"><ul class="nav nav-pills" style="height:30px;"><li><a href="#"><img onclick="onClickSettingsIcon(0,'+deviceID+')" style="height:25px; width:25px;" src="'+icons_path+'/green_icon_off.png"></a></li><li><a href="#"><img onclick="onClickSettingsIcon(1,'+deviceID+')" style="height:25px; width:25px;" src="'+icons_path+'/blue_icon_off.png"></a></li><li><a href="#"><img onclick="onClickSettingsIcon(2,'+deviceID+')" style="height:25px; width:25px;" src="'+icons_path+'/yellow_icon_off.png"></a></li><li><a href="#"><img onclick="onClickSettingsIcon(3,'+deviceID+')" style="height:25px; width:25px;" src=".'+icons_path+'/orange_icon_off.png"></a></li></ul></ul></li></ul></div></div>';
	}
}


function onClickRadioAccel(value)
{
	SettingAccel = value;
}


function sendXMLSettingsRequest(deviceID)
{
	document.getElementById('dinamic_container').innerHTML = '';
	var req;

	if (window.XMLHttpRequest)	req = new XMLHttpRequest();
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
			if (req.readyState == 4 && req.status == 200)	{	bild_settings_list(req.responseXML, deviceID);	}	/* as DOM format */
		};
	
		req.open('POST', './xmlhttp_settings.php?id='+deviceID, true);
		req.send(null);
	}
	else alert("don't work AJAX");
}


function sendXMLUpdateRequest()
{
	var device = findById( arrayDevices, id_setting_update );
	document.getElementById('traker_alias'+id_setting_update).innerHTML = document.getElementById('deviceAliasValue').value;
	document.getElementById('img'+id_setting_update).src =icons_path+SettingColor+'_icon_off.png';
	device.setIcon(SettingColor);

	var aliasSettingValue = document.getElementById('deviceAliasValue').value;
	var accelSettingValue = SettingAccel;
	var intervalSettingValue = document.getElementById('trackbar').value;
	var iconSettingValue = document.getElementById('trackbar').value;
	
	var req;

	if (window.XMLHttpRequest)	req = new XMLHttpRequest();
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
			if (req.readyState == 4 && req.status == 200)	{	}	/* as DOM format */
		};
	
		req.open('POST', './xmlhttp_settings_update.php?id='+id_setting_update+'&alias='+aliasSettingValue+'&status='+accelSettingValue+'&time='+intervalSettingValue+'&icon='+SettingColor, true);
		req.send(null);
	}
	else alert("don't work AJAX");
}