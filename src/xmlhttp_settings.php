<?php

	header('Content-type: text/xml');

	require_once "./class/class_db.php";

	function qwerty ($device_ID)
	{
		/* import user ID here */
		
		$ini_array = parse_ini_file("./config.ini", true);
	
		$db_host = $ini_array['database']['host'];
		$db_name = $ini_array['database']['name'];
		$db_user = $ini_array['database']['user'];
		$db_pass = $ini_array['database']['pass'];
	
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		if (!$db->IsConnected())
			exit("Error connect to DB!");
		
 		$sql = "SELECT `device_alias`, `device_accel_stat`, `device_time_interval`, `device_icon` FROM `tr_device` WHERE `device_ID` = '".$device_ID."'";
		$tmp = $db->query($sql, 0);

		if($tmp)
		{		
			$dom = new DomDocument('1.0'); 
			$settings = $dom->appendChild($dom->createElement('settings')); 
			
			foreach ($tmp as $key => $val)
			{	
				$name = $settings->appendChild($dom->createElement($key)); 
				$name->appendChild( 
								$dom->createTextNode($val)); 
			}
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
 	
	$qert = $_GET['id'];
	
	qwerty($qert);

?>