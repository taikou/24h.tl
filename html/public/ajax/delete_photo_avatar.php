<?php
session_start();
require('../../class_ajax_request/classAjax.php');
include('../../application/functions.php'); 
include('../../application/DataConfig.php');
error_reporting(0);


if ( isset( $_GET['token_id'] ) ) {
	if ( isset( $_SESSION['authenticated'] ) ) {
			/*
			 * --------------------------
			 *   Require File
			 * -------------------------
			 */
			require_once('../../class_ajax_request/classAjax.php');
			/*
			 * ----------------------
			 *   Instance Class
			 * ----------------------
			 */
			$obj   = new AjaxRequest();	
			
			/*
			 * --------------------------
			 *   Root of Photo
			 * -------------------------
			 */
			$root           = '../avatar/';
			$photo_id       = $_GET['token_id'];
			$photo_id_large = 'large_'.$_GET['token_id'];
			$infoUser       = $obj->infoUserLive( $_SESSION['authenticated'] );
			$imgOld         = $infoUser->avatar;
			
			/*
			 * --------------------------
			 *   Folder permissions
			 * -------------------------
			 */
			 if( $imgOld == $photo_id ) {
			 	chmod( $root.$photo_id, 0777 );
			
				 if ( file_exists( $root.$photo_id ) )  {
					 unlink( $root.$photo_id );
					 unlink( $root.$photo_id_large );
					 
					 /* Update Database */
					 $new_avatar=DEFAULT_AVATARS[mt_rand(0,count(DEFAULT_AVATARS)-1)];
					 error_log(DEFAULT_AVATARS);
					 $obj->uploadAvatar( $new_avatar );
					 
					 echo $new_avatar;
				 }
			 }
	}//<-- session
}//<-- if token id
?>