<?php
session_start();
//error_reporting(0);
/*
	 * --------------------------
	 *   Require/Include Files
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
    include_once('../../application/functions.php'); 
	include_once('../../application/DataConfig.php');
	
	$obj       = new AjaxRequest();
	$settings  = $obj->getSettings();
	
if ( $settings->email_verification == '1' ) {
	require_once('../../phpmailer/PHPMailerAutoload.php');
	include_once('../../public/ajax/sign_up_verify.php'); 
	
} else if ( $settings->email_verification == '0' ) {
	include_once('../../public/ajax/sign_up_no_verify.php'); 
}
 ?>