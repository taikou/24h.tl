<?php
/****************************************
 * 
 *  Author : Miguel Vasquez
 *  File   : index.php
 *  
 * Session::init() = SESSION START
 * Bootstrap::run( new Request ) = Request
 * $obj->getSettings() = Settings default Admin
 * 
 **************************************/
 
 if (($_SERVER['HTTP_X_FORWARDED_PROTO'])!=='https') {
//	print_r($_SERVER);exit;
    header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
    exit;
}
	error_reporting( 1 );
	/* Define absolute paths */
	define( 'DS', DIRECTORY_SEPARATOR );
	define( 'ROOT', realpath( dirname( __FILE__ ) ) . DS );
	define( 'APP_PATH', ROOT . 'application' . DS );
	
	// Login Social
	define( 'SOCIAL_PATH', ROOT . 'oauth' . DS . 'twitter' . DS );
	
//	date_default_timezone_set('Asia/Tokyo');

	/* REQUIRE FILES */
	
try {
    require_once APP_PATH . 'Config.php';
	require_once APP_PATH . 'SPDO.php';
	require_once APP_PATH . 'ModelBase.php';
    require_once APP_PATH . 'Request.php';
    require_once APP_PATH . 'Bootstrap.php';
    require_once APP_PATH . 'Controller.php';
    require_once APP_PATH . 'View.php';
    require_once APP_PATH . 'Sessions.php';
	require_once APP_PATH . 'Db.php';
	require_once APP_PATH . 'AdminSettings.php';
	require_once APP_PATH . 'functions.php';
	
	// Login Social
	require_once SOCIAL_PATH . 'twitteroauth.php';
	
	/*-----------------
	 * ADMIN SETTINGS
	 * ---------------
	 */
	$obj       = new adminSettings();
	$settings  = $obj->getSettings();
	
	require_once APP_PATH . 'DataConfig.php';
	
    //<-* SESSION START *->
    Session :: init();
	
	//<-* Languages *->
	_Function :: getLang();

if($_GET['debug']=='on'){
	$_SESSION['debug']=true;
}elseif($_GET['debug']=='off'){
	$_SESSION['debug']=false;
}
	
	//<- * REQUEST * ->
    Bootstrap :: run( new Request );
	
	$_SESSION['LANG']=''; //セッション容量対策
//	error_log('clear');
}
catch( Exception $e ) {
    echo $e->getMessage();
}
?>