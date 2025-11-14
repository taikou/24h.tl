<?php
error_reporting(1);
session_start();
if(preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){
	require_once('../../class_ajax_request/classAjax.php');
   $obj = new AjaxRequest();

	$obj->signOut();
	setcookie('24h_app_autosessid','');
}

if ( isset( $_GET['logout'] ) && $_GET['logout'] == 'out')
{
	$_SESSION[]=array();
	session_destroy();
	

	session_start();
	echo 'OK';
	exit(0);
}
?>