<?php

	header('Content-type: text/xml');

	require_once "./class/class_db.php";
	require_once './sys/jframe.php';

	function qwerty ($device_ID, $device_alias, $device_status, $device_time, $device_icon)
	{
		$user =& JFactory::getUser();
		$userID = $user->get('id');
		
		$ini_array = parse_ini_file("./config.ini", true);
	
		$db_host = $ini_array['database']['host'];
		$db_name = $ini_array['database']['name'];
		$db_user = $ini_array['database']['user'];
		$db_pass = $ini_array['database']['pass'];
	
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		if (!$db->IsConnected())
			exit("Error connect to DB!");
		
		$sql = "UPDATE `tr_device` SET `device_alias` ='".$device_alias."',  `device_accel_stat` ='".$device_status."',  `device_time_interval` ='".$device_time."', `device_icon` ='".$device_icon."'  WHERE `device_ID` = '".$device_ID."'";

		if (!($db->query($sql, 0))) 
			{	
				XMLRPC_error("1", "update error: ", KD_XMLRPC_USERAGENT);
				unset($db);	
				return;
			}
		
		unset($db); 
	}
 	
	$qert = $_GET['id'];
	$alias = $_GET['alias'];
	$status = $_GET['status'];
	$time = $_GET['time'];
	$icon = $_GET['icon'];
	
	qwerty($qert, $alias, $status, $time, $icon);

?>