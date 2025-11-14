<?php
session_start();
error_reporting(0);
if ( 
	isset( $_GET['_bgPosition'] ) 
	&& !empty( $_GET['_bgPosition'] )
) {
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
		$obj               = new AjaxRequest();
		
		$query = $obj->editBgPosition();
		
		if( $query == 1 ) {
			 echo json_encode( array( 'action' => 1, 'result' => $_SESSION['LANG']['saved_successfully'] ) ); 
		}//<--- $QUERY
		else {
			 echo json_encode( array( 'action' => 0, 'result' => $_SESSION['LANG']['no_changes'] ) ); 
		}
  }//<-- SESSION
}//<-- if token id
?>