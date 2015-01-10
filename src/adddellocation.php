<?php

	require_once "./class/class_db.php";
	require_once './sys/jframe.php';

	function qwerty ($case)
	{
		$dolgota = rand(30,39); $shirota = rand(50,59);
	
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
		
		switch($case)	{
		case 1000:
					$sql = "INSERT INTO `tr_location` (`location_lat_log`, `location_deviceID`) VALUES ";
					for ($i=0; $i<1000; $i++)
						{
							$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10'), ";
						}
					$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10')";
					$tmp = $db->query($sql, 0);
				break;
		case 100:
					$sql = "INSERT INTO `tr_location` (`location_lat_log`, `location_deviceID`) VALUES ";
					for ($i=0; $i<100; $i++)
						{
							$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10'), ";
						}
					$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10')";
					$tmp = $db->query($sql, 0);

				break;
		case 10:
					$sql = "INSERT INTO `tr_location` (`location_lat_log`, `location_deviceID`) VALUES ";
					for ($i=0; $i<10; $i++)
						{
							$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10'), ";
						}
					$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10')";
					$tmp = $db->query($sql, 0);

				break;
		case 1:
					$sql = "INSERT INTO `tr_location` (`location_lat_log`, `location_deviceID`) VALUES ";
					$sql = $sql."('".((rand(0,999990)/1000000)+$shirota).",".((rand(0,999990)/1000000)+$dolgota)."', '10')";
					$tmp = $db->query($sql, 0);

				break;
		case 0:
					$sql = "DELETE FROM `tr_location` WHERE `location_deviceID` = '10'";
					$tmp = $db->query($sql, 0);
				break;
					}

		
		$sql = "SELECT COUNT(*) FROM `tr_location` WHERE `location_deviceID` = '10'";
		$tmp = $db->query($sql, 0);
		echo ("записей в таблице - ".$tmp);
		
		unset($db); 
	}
 	
	$case = $_GET['case'];
	
	qwerty($case);

?>