<?php 

/* DON'T EDIT THIS CODE! */
/* This code is used for getting variables from joomla to external code - iframe */
/* JUST INCLUDE IT IN YOURS IFRAME */

define('_JEXEC', 1 );
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT'] );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
require (JPATH_BASE.DS.'libraries/joomla/factory.php');
 
JFactory::getApplication('site')->initialise();


?>