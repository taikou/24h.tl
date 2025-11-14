<?php
session_start();
error_reporting(0);

if ( isset( $_POST['delete'] ) && $_POST['delete'] == 'true') {
	/*
	 * --------------------------
	 *   Require File
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	$path_avatar = "../avatar/";
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj         = new AjaxRequest();
	$infoUser    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$imgOld      = $path_avatar.$infoUser->avatar;
	$delete      = $obj->deleteAccount();

	/*
	 * --------------------------
	 *   Delete Avatar
	 * -------------------------
	 */
	if( isset( $delete ) ){
		
		if ( file_exists( $imgOld ) && !in_array(basename($imgOld),DEFAULT_AVATARS) ){
				unlink( $imgOld );
			}
			
		session_destroy();
	    echo 'ok';
	    exit(0);
	}
	else {
		return false;
	}
	
}
?>