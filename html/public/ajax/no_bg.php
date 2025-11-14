<?php
session_start();
error_reporting(0);

if ( isset( $_POST['bg'] ) )
{
if ( isset( $_SESSION['authenticated'] ) )
	{
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
		$obj             = new AjaxRequest();	
		$post_id_session = $_POST['id_session'];
		$root            = '../backgrounds/';
		$photo_id        = $_POST['bg'];
		$infoUser        = $obj->infoUserLive( $_SESSION['authenticated'] );
		$imgOld          = $infoUser->bg;
		$defaults        = array( 
		'photo_0.jpg', 
		'photo_1.jpg',
		'photo_2.jpg',
		'photo_3.jpg',
		'photo_4.jpg',
		'photo_5.jpg',
		'photo_6.jpg',
		'photo_7.jpg',
		'photo_8.jpg',
		'photo_9.jpg',
		'photo_10.jpg',
		'photo_11.jpg',
		'photo_12.jpg',
		'photo_13.jpg',
		'photo_14.jpg',
		'photo_15.jpg'  );
		
		/*
		 * --------------------------
		 *   folder permissions
		 * -------------------------
		 */
		  if( $imgOld == $photo_id ) {
			  	chmod( $root.$photo_id, 0777 );
			 if ( file_exists( $root.$photo_id ) && !preg_match('/^photo_[0-9]{1,4}.jpg$/',$photo_id) ) {
				unlink( $root.$photo_id );
			 }
			 
			 $obj->bottomLess();
			 
			echo 'TRUE';
		  }
		
			
    }//<-- session
}//<-- if token id
?>