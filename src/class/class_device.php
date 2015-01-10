<?php

Class Device{

	private $device_ID = "";
	private $device_alias = "";
	private $device_type = "";
	private $device_hw_version = "";
	private $device_sw_version = "";
	private $device_time_interval = "";
	private $device_sms_pass = "";
	private $device_stat = "";			/* registered/unregistered */
	private $device_accel_stat = "";
	private $device_phone1 = "";
	private $device_phone2 = "";
	private $device_icon = "";
	
	private $deviceLocationCount = 0;
	
	private $deviceLastLocation = "";
	
	private $deviceLocation = array();
	
	
	function __construct($ID="")
	{
		$ini_array = parse_ini_file("./config.ini", true);
		
		$db_host = $ini_array['database']['host'];
		$db_name = $ini_array['database']['name'];
		$db_user = $ini_array['database']['user'];
		$db_pass = $ini_array['database']['pass'];
		
		$deviceTable = $ini_array['table']['device'];
		$locationTable = $ini_array['table']['location'];

		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		if (!$db->IsConnected())
			exit("Error connection");
		//TODO::add exeption
		
		$this->device_ID = $ID;
		
		
		$sql = "SELECT `device_alias` FROM  ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_alias = $db->query($sql, 0);
		
		$sql = "SELECT `device_type` FROM  ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_type = $db->query($sql, 0);
		
		$sql = "SELECT `device_hw_version` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_hw_version = $db->query($sql, 0);
		
		$sql = "SELECT `device_sw_version` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_sw_version = $db->query($sql, 0);

		$sql = "SELECT `device_sms_pass` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_sms_pass = $db->query($sql, 0);
		
		$sql = "SELECT `device_accel_stat` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_accel_stat = $db->query($sql, 0);

		$sql = "SELECT `device_stat` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_stat = $db->query($sql, 0);
		
		$sql = "SELECT `device_icon` FROM ".$deviceTable." WHERE `device_ID` = '".$ID."'";
		$this->device_icon = $db->query($sql, 0);
		
		$sql = "SELECT MAX(`location_time`) FROM ".$locationTable." WHERE `location_deviceID` = '".$ID."'";
		$this->deviceLastLocationTime = $db->query($sql, 0);

		$sql = "SELECT `location_lat_log` FROM ".$locationTable." WHERE `location_deviceID` = '".$ID."' AND `location_time` = '".$this->deviceLastLocationTime."'";
		$this->deviceLastLocation = $db->query($sql, 0);
		
		$sql = "SELECT `location_speed` FROM ".$locationTable." WHERE `location_deviceID` = '".$ID."' AND `location_time` = '".$this->deviceLastLocationTime."'";
		$this->deviceLastLocationSpeed = $db->query($sql, 0);
		
		$sql = "SELECT `location_altitude` FROM ".$locationTable." WHERE `location_deviceID` = '".$ID."' AND `location_time` = '".$this->deviceLastLocationTime."'";
		$this->deviceLastLocationAltitude = $db->query($sql, 0);
		
		$sql = "SELECT `location_satellites_number` FROM ".$locationTable." WHERE `location_deviceID` = '".$ID."' AND `location_time` = '".$this->deviceLastLocationTime."'";
		$this->deviceLastLocationSatellites = $db->query($sql, 0);
    }
    
	public function getDeviceID()			{ return $this->device_ID; }
	
	public function getDeviceAlias()		{ return $this->device_alias; }
	
	public function getDeviceType()			{ return $this->device_type; }
	
	public function getDeviceHWVersion()	{ return $this->device_hw_version; }
	
	public function getDeviceSWVersion()	{ return $this->device_sw_version; }
	
	public function getDeviceTimeInterval()	{ return $this->device_time_interval; }

	public function getDeviceSMSPass()		{ return $this->device_sms_pass; }
	
	public function	getRegistrationStatus()	{ return $this->device_stat; }

	public function getAccelerometerStatus() { return $this->device_accel_stat; }
	
	public function getDevicePhoneNumber1()	{ return $this->device_phone1; }
	
	public function getDevicePhonenumber2()	{ return $this->device_phone2; }
	
	public function getDeviceIcon()			{ return $this->device_icon; }
    
    public function getDeviceLocation() 	{ return $this->deviceLocation; }
	
	public function getDeviceLocationTime()	{ return $this->deviceLocationTime; }
	
	public function getDeviceLocationSpeed() { return $this->deviceLastLocationSpeed; }    
	
	public function getDeviceLocationCount() { return $this->deviceLocationCount; }
    
    public function getDeviceLastLocation() { return $this->deviceLastLocation; }
	
	public function getDeviceLastLocationTime() { return $this->deviceLastLocationTime; }
	
	public function getDeviceLastLocationSpeed() { return $this->deviceLastLocationSpeed; }
	
	public function getDeviceLastLocationAltitude() { return $this->deviceLastLocationAltitude; }
	
	public function getDeviceLastLocationSatellites() { return $this->deviceLastLocationSatellites; }
	
	public function setDeviceAlias($alias)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_alias=".$alias."
									WHERE `device_ID` = '".$this->device_ID."'";
		$db->query($sql, 0);
		
		unset($db);
	}

	public function setDeviceTimeInterval($minutes)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_time_interval=".$minutes."
									WHERE `device_ID` = '".$this->device_ID."'";
		$db->query($sql, 0);
		
		unset($db);
	}
	
	public function setDeviceSMSPass($sms)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_sms_pass=".$sms."
									WHERE `device_ID` = '".$this->device_ID."'";
		$db->query($sql, 0);
		
		unset($db);
	}
	
	public function setRegistrationStatus($status)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_stat=".$status."
									WHERE `device_ID` = '".$this->device_ID."'";
		$db->query($sql, 0);
		
		unset($db);
	}

	public function setAccelerometerStatus($status)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_accel_stat=".$status."
									WHERE `device_ID` = '".$this->device_ID."'";
		
		$db->query($sql, 0);
		
		unset($db);
	}
	
	public function setDevicePhoneNumber1($number)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_phone1=".$number."
									WHERE `device_ID` = '".$this->device_ID."'";
		
		$db->query($sql, 0);
		
		unset($db);
	}
	
	public function setDevicePhoneNumber2($number)
	{
		$db = new MySQL($db_host, $db_user, $db_pass, $db_name);
		$db->IsConnected();
		
		$sql = "UPDATE `".$device_table."` SET device_phone2=".$number."
									WHERE `device_ID` = '".$this->device_ID."'";
		
		$db->query($sql, 0);
		
		unset($db);
	}

	public function setDeviceLocation($location)
	{
		/* code here */
	}
	
}

?>
