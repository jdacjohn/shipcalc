<?php
global $root;
//Get configuration variables used throughout project
require_once($root . '_config/config.inc.php');

//redirect to offline message if performing updates
/*if (strpos($_SERVER['PHP_SELF'],'offline.php') === false && !isset($_REQUEST['debug'])) {
	header(PROJECT_URL.'/offline.php');
}*/

//turn off debugging for production
//if (ARE_WE_LIVE) {
//    unset($_REQUEST['debug']);
//    putenv("gDebug= false");
//} else {
//    if (isset($_REQUEST['debug'])) {
//        $debugOn = trim($_REQUEST['debug']);
//    } else {
//        $debugOn = 'false';
 //   }
 //   putenv("gDebug=$debugOn");
 //   if (getenv('gDebug')) {
 //       ob_start();
 //   }
//}

// Define some appRelatedConstants
define('METERS_PER_MILE', 1609.34);

// Array for carrier selection criteria
$carrierCriteria = array(
    'forty8' => 'N',
    'ak' => 'N',
    'hi' => 'N',
    'ovs' => 'N',
    'pov' => 'N',
    'mc' => 'N',
    'rv' => 'N',
    'atvutv' => 'N',
    'boat' => 'N',
    'open' => 'Y',
    'enclosed' => 'N'
);
    
//Include common functions
//require_once($root . '_includes/common.php');
require_once($root . '_includes/app.db.php');
require_once($root . '_includes/app_functions.php');
//require_once(WEB_ROOT.PROJECT_DIR.'/_includes/addURLParamFunction.inc.php');
//require_once(WEB_ROOT.PROJECT_DIR.'/_includes/stringSwapClass.inc.php');
// require_once(WEB_ROOT.PROJECT_DIR.'/_includes/project-functions.inc.php');

//Some application level variables
$arrErr = getParam(POSTBACK_PARAMETER_PREFIX.'error', true);

//To make self referrencing easy
$gFullSelfRequest = 'http'.((isset($_SERVER['HTTPS'])) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; //With QS
$gQualifiedSelfRequest = 'http'.((isset($_SERVER['HTTPS'])) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; //Without QS

//session_cache_expire(60); //60 minute page expires
ini_set('session.gc_maxlifetime', 3600); //60 minute session expires
session_set_cookie_params(3600);
session_start(); //start the session

if (!isset($_SESSION[SESSION_NAME])) {
    //printVarIfDebug('clearing $_SESSION[SESSION_NAME] as part of startup procedure in app_start..inc.php.', getenv('gDebug'));
    reset_session();
}

define('APP_LOADED',1);
