<?php
session_start();
error_reporting(0);
if( 
		isset ( $_GET['since_id'] ) 
		&& !empty ( $_GET['since_id'] ) 
		&& isset( $_GET['query'] )	
) {
	/*
	 * --------------------------
	 *   Require/Include Files
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	include_once('../../application/functions.php'); 
	include_once('../../application/DataConfig.php');	

if( isset( $_GET ) && $_SERVER['REQUEST_METHOD'] == "GET" && isset( $_SESSION['authenticated'] ) ) {
 	$since_id     = is_numeric( $_GET['since_id'] ) ? $_GET['since_id'] : die();
	
	$_array       = array();
	
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj          = new AjaxRequest();
	$infoUser     = $obj->infoUserLive( $_SESSION['authenticated'] );
	$getPosts     = $obj->search( $_GET['query'], ' 
	&& P.status = "1" &&P.date > (utc_timestamp() - interval 1 day) && P.id > '.$since_id.' ' , 'GROUP BY P.id ORDER BY P.id ASC' , null, 
	$_SESSION['authenticated'] );
    
    $count = count( $getPosts );
	
	//<------------------- * * -------------------->
	if( $count != 0 ) {
		for ( $i = 0; $i < $count; ++$i ) {
			
			$_idUser = $getPosts[$i]['user_id'];
			
			if( $getPosts[$i]['repost_of_id'] != 0 ) {
				$nameUser                     = $getPosts[$i]['name'];
				$idPost                       = $getPosts[$i]['id'];
				$getPosts[$i]['type_account'] = $getPosts[$i]['rp_type_account'];
				$getPosts[$i]['username']     = $getPosts[$i]['rp_username'];
				$getPosts[$i]['name']         = $getPosts[$i]['rp_name'];
				$getPosts[$i]['avatar']       = $getPosts[$i]['rp_avatar'];
				$getPosts[$i]['user']         = $getPosts[$i]['rp_user'];
				$getPosts[$i]['id']           = $getPosts[$i]['rp_id'];
				
			} else {
				$nameUser                     = null;
				$idPost                       = $getPosts[$i]['id'];
				$getPosts[$i]['type_account'] = $getPosts[$i]['type_account'];
				$getPosts[$i]['username']     = $getPosts[$i]['username'];
				$getPosts[$i]['name']         = $getPosts[$i]['name'];
				$getPosts[$i]['avatar']       = $getPosts[$i]['avatar'];
				$getPosts[$i]['user']         = $getPosts[$i]['user'];
				$getPosts[$i]['id']           = $getPosts[$i]['id'];
		}
			
		/*
		 * -------------------------------------
		 *  POST DETAILS / EXPAND / FAVS ETC
		 * -------------------------------------
		 */
		include( 'posts_ajax_timeline.php' );
			
		}//<--- * LOOP FOR * --->
	}//<<<-- COUNT != 0

	if( $count <= 1 ) {
		$new_posts = $_SESSION['LANG']['one_post_new'];
	} else if( $count >= 2 ) {
		$new_posts = $_SESSION['LANG']['post_new'];
	}
	
echo json_encode( array ( 'posts' => $_array, 'total' => $count, 'html' => $new_posts ) ); 

 }//<-- $_SERVER['REQUEST_METHOD']
}//<---isset ( $_GET['offset']) &&
 ?>