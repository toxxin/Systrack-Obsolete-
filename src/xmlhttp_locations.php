<?php

	header('Content-type: text/xml');

	require_once "./class/class_db.php";
	require_once './sys/jframe.php';

	function getLastLocation ($device_ID)
	{
		$ini_array = parse_ini_file("./config.ini", true);
	
		$db_host = $ini_array['database']['host'];
		$db_name = $ini_array['database']['name'];
		$db_user = $ini_array['database']['user'];
		$db_pass = $ini_array['database']['pass'];
		
		$locationTable = $ini_array['table']['location'];
	
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		if (!$db->IsConnected())
			exit("Error connect to DB!");
		
 		$sql = "SELECT `location_lat_log`, `location_time` FROM `".$locationTable."` WHERE `location_deviceID` = '".$device_ID."' ORDER BY `location_time` DESC LIMIT 1";
		$tmp = $db->query($sql, 0);

		if(count ($tmp) == 1)
		{	
			$dom = new DomDocument('1.0'); 

			$point = $dom->appendChild($dom->createElement('point')); 

			$date = $point->appendChild($dom->createElement('date')); 
			$date->appendChild($dom->createTextNode($tmp->location_time)); 

			$coordinate = $point->appendChild($dom->createElement('coordinate')); 
			$coordinate->appendChild($dom->createTextNode($tmp->location_lat_log)); 

			$dom->formatOutput = true; 
			$dom_string = $dom->saveXML();

			echo $dom_string;
		}
		else
		{
			echo "no data";
		}

		unset($db); 
	}
 	
	function getPeriodLocation ($device_ID, $time_period)
	{
		$ini_array = parse_ini_file("./config.ini", true);
	
		$db_host = $ini_array['database']['host'];
		$db_name = $ini_array['database']['name'];
		$db_user = $ini_array['database']['user'];
		$db_pass = $ini_array['database']['pass'];
		
		$locationTable = $ini_array['table']['location'];
	
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		if (!$db->IsConnected())
			exit("Error connect to DB!");
		
		$sql = "SELECT `location_lat_log`, `location_time` FROM ".$locationTable." WHERE `location_deviceID` = '".$device_ID."' AND date_format(`location_time`, '%Y%m%d%H%i%s') > date_format(date_add(now(), interval -1 ".$time_period."), '%Y%m%d%H%i%s') ORDER BY `location_time`";		
		$tmp = $db->query($sql, 0);

		if(is_array($tmp))
		{		
			$dom = new DomDocument('1.0'); 
			$points = $dom->appendChild($dom->createElement('points')); 
			
			foreach ($tmp as $obj)
			{	
				$point = $points->appendChild($dom->createElement('point')); 

				$date = $point->appendChild($dom->createElement('date')); 
				$date->appendChild( 
								$dom->createTextNode($obj->location_time)); 

				$coordinate = $point->appendChild($dom->createElement('coordinate')); 
				$coordinate->appendChild( 
								$dom->createTextNode($obj->location_lat_log)); 
			}
			$dom->formatOutput = true; 
			$dom_string = $dom->saveXML();

			echo $dom_string;

		}
		else if(count ($tmp) == 1)
		{	
			$dom = new DomDocument('1.0'); 
			$points = $dom->appendChild($dom->createElement('points')); 

			$point = $points->appendChild($dom->createElement('point')); 

			$date = $point->appendChild($dom->createElement('date')); 
			$date->appendChild( 
							$dom->createTextNode($tmp->location_time)); 

			$coordinate = $point->appendChild($dom->createElement('coordinate')); 
			$coordinate->appendChild( 
							$dom->createTextNode($tmp->location_lat_log)); 

			$dom->formatOutput = true; 
			$dom_string = $dom->saveXML();

			echo $dom_string;
		}
		else
		{
			echo " no data";
		}

		unset($db); 
	}
	
	$device_id = $_GET['id'];
	$function = $_GET['function'];
		
	switch ($function)
	{
		case 'day'	: 	$period = 'day';
						getPeriodLocation($device_id, $period);
						break;
		
		case 'month': 	$period = 'month';
						getPeriodLocation($device_id, $period);
						break;
		
		case 'hour'	: 	$period = 'hour';
						getPeriodLocation($device_id, $period);
						break;
		
		case 'week'	: 	$period = 'week';
						getPeriodLocation($device_id, $period);
						break;

		case "last"	:	getLastLocation($device_id);
						break;
	}
	
	

?>