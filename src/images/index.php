<?php
session_start();

require_once './class/class_db.php';
require_once './class/class_device.php';


if (isset($_SESSION['lng']))
{
	setcookie("lng", $_SESSION['lng']);
	$language = $_SESSION['lng'];
}
else 
{
	if(isset($_COOKIE['lng']))
	{
		$language = $_COOKIE['lng'];
	}
	else
	{
		setcookie("lng", "en");
		$language = "en";
	}
}

//require_once "./auth.php";
?>




<!DOCTYPE html>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="/floatbox/floatbox.css" />
	<script type="text/javascript" src="/floatbox/floatbox.js"></script>
</head>
<body>
<?php
include 'menu.php';
?>
<br><br><br>
  
  <!-- Slide show -->
  <div class="fbCycler" style="height:420px;">
   <div>
     <img src="http://1.bp.blogspot.com/-DsILXNpmzHE/TdwEKeG9KyI/AAAAAAAAAyc/6Lj9owsjouM/s1600/dr+hugh+based.jpg" alt="" />
     <span>dirt biking to Rom Pho Tai</span>
   </div>
   <div>
     <img data-fb-src="http://3dcgac.files.wordpress.com/2011/05/btlc43.jpg" alt="" />
     <span>the road less travelled</span>
   </div>
   <img data-fb-src="http://meetoncruise.com/images/fblogin%20Step1.jpg" alt="" />
   <span>etc...</span>
 </div>
 
 
 <!-- Slide show -->
 <a href="registration.php" title="Registration form" class="floatbox" data-fb-options="width:325 height:460 scrolling:no zoomSource:demo_form.png caption:`Registration form` captionPos:tc showClose:false">Registration Form</a>
 <div>
 
 <h3>follow us:</h3>
 <a href="#"><img src="http://jackstow.com/blog/image.axd?picture=2011%2F10%2Flists.jpg" alt=""></a>
 
</body>
</html>