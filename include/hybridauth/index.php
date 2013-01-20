<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

// ------------------------------------------------------------------------
//	HybridAuth End Point
// ------------------------------------------------------------------------

define('PHPWG_ROOT_PATH', '../../../../');
include_once(PHPWG_ROOT_PATH.'include/common.inc.php');

require_once( "Hybrid/Auth.php" );
require_once( "Hybrid/Endpoint.php" ); 

Hybrid_Endpoint::process();
