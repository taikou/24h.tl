<?php
session_start();
error_reporting(0);
if( 
		isset ( $_GET['postId'] ) 
		&& isset ( $_GET['token'] )
) {

  if( isset( $_GET ) && $_SERVER['REQUEST_METHOD'] == "GET" ) {
   	
	$favoriteArr = array();
	$replyArr    = array();
   	
	$_postId = is_numeric( $_GET['postId'] ) ? $_GET['postId'] : die();
	$_token  = !preg_match('/[^a-z0-9-\_\.]/i', $_GET['token'] ) ? $_GET['token'] : die();
	
	/*
	 * --------------------------
	 *   Require/Include Files
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	include_once('../../application/functions.php'); 
	include_once('../../application/DataConfig.php');	
	
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj            = new AjaxRequest();
	$getMedia       = $obj->getMedia( $_postId, $_token );
	$getFavorites   = $obj->getFavorites( $_postId );
	$countFavs      = count( $getFavorites );
	$getReply       = $obj->getReply( $_postId );
	$countReply     = count( $getReply );
	$verifyPost     = $obj->checkPost( $_postId, $_token ) ? 1 : 0;
	$getRepost      = $obj->getRepostUser( $_postId );
	
	/* Url */
	$urlStatus = URL_BASE.$getMedia[0]['username'].'/status/'.$getMedia[0]['id'];
	
	/*
	 * --------------------
	 * Verify Post
	 * --------------------
	 */
	 if( $verifyPost == 0 ) {
	 	return false;
	 }
	
	//================================================//
	//                   * Favorites *               =//
	//================================================//
	if( $countFavs != 0 ) {
		for ( $i = 0; $i < $countFavs; ++$i ) { 
			$favoriteArr[] = '<a href="'.URL_BASE.$getFavorites[$i]['username'] .'" class="myicon-right showTooltip openModal" data-id="'.$getFavorites[$i]['id'].'" title="@'.$getFavorites[$i]['username'].'" data-toggle="tooltip" data-placement="top"> <img src="'.URL_BASE.'thumb/40-40-public/avatar/'.$getFavorites[$i]['avatar'] .'" class="img-rounded" width="20"> </a>';
		}
	}//<--- 
	
	//================================================//
	//                   * Replys *                  =//
	//================================================//
	if( $countReply != 0 ) {
		for ( $u = 0; $u < $countReply; ++$u ) {
			//<--- * User Verified * --->
			if( $getReply[$u]['type_account'] == 1 ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else  {
					$verified = null;
				}
				//<--- * Delete Reply* --->
			if( $_SESSION['authenticated'] == $getReply[$u]['id'] ) {
					$removeReply = ' <div class="pull-right small"> <a href="javascript:void(0);" data="'.$getReply[$u]['idReply'].'" class="link-post showTooltip removeReply" title="'. $_SESSION['LANG']['delete'] .'" data-toggle="tooltip" data-placement="top"> <i class="glyphicon glyphicon-remove size-icon"></i> </a> </div>';
				} else {
					$removeReply = null;
				}
			
			$replyArr[] = "<div class='media li-group reply-list'>
				  	<a class='pull-left openModal' data-id='".$getReply[$u]['id']."' href='".URL_BASE.$getReply[$u]['username']."'>
				    	<img class='media-object img-circle' src='".URL_BASE.'thumb/80-80-public/avatar/'.$getReply[$u]['avatar']."' width='40'>
				  	</a>
				  	<div class='media-body'>
							".$removeReply."
						<h6 class='media-heading'>
							<a href='".URL_BASE.$getReply[$u]['username']."' data-id='".$getReply[$u]['id']."' class='username-posts openModal'><strong>".stripslashes( $getReply[$u]['name'] )."</strong> ".$verified."</a> <small class='username-ui'>@".$getReply[$u]['username']."</small>
							</h6>
				    	<p class='list-grid-block p-text'>
				    		"._Function::checkText( $getReply[$u]['reply'] )."
				    	</p>
				    	<span class='timestamp timeAgo small sm-font sm-date' data='".date('c', strtotime( $getReply[$u]['date'] ) )."'></span>
				  	</div>
				</div>";
		}
	}//<---
		
	/* Report Post */
	if(  isset( $_SESSION['authenticated'] ) && $_SESSION['authenticated'] != $getMedia[0]['user'] ) {
		$report    = '<li class="list-breadc"> <a href="javascript:void(0);" class="iReport reportPost" data="'.$getMedia[0]['id'].'" data-token="'.$getMedia[0]['token_id'].'"> <i class="icon-flag myicon-right"></i> '.$_SESSION['LANG']['report'].' </a> </li>';
	
	}
	
	//<----- Output data
	echo json_encode( array ( 
			'media' => '
			<ol class="breadcrumb li-group margin-bottom-zero padding-left-zero padding-right-zero">
			  <li class="list-breadc"><span class="fa fa-thumbs-o-up myicon-right"></span> '.$countFavs.'</li>
			  <li class="list-breadc"><span class="fa fa-retweet myicon-right"></span> '.$getRepost[0]['totalRepost'].'</li>
			  <li class="favs-list"></li>
			</ol>
			 
			<ol class="breadcrumb li-group padding-left-zero padding-right-zero">
			<li class="list-breadc datetime" data-timestamp="'.date('Y-m-d\TH:i:s', strtotime( $getMedia[0]['date'] ) ).'.000Z">'.date('Y/m/d H:i', strtotime( $getMedia[0]['date'] ) ).'</li>
			<li class="list-breadc"><a href="'.URL_BASE.$getMedia[0]['username'].'/status/'.$getMedia[0]['id'].'">'.$_SESSION['LANG']['details'].'</a></li>
			'.$report." 
			", 
			'favs' => $favoriteArr, 
			'error' => 0, 
			'replys' => $replyArr 
	) 
	); 

  }//<---- ISSET GET
}//<-- ISSET
?>