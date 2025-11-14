<?php
session_start();
error_reporting(0);
if(  isset ( $_GET['id_user'] ) && !empty ( $_GET['id_user'] ) ) {
	
	/*
	 * --------------------------
	 *   Require File
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	include_once('../../application/functions.php'); 
	include_once('../../application/DataConfig.php');	

if( isset( $_GET ) && $_SERVER['REQUEST_METHOD'] == "GET" ) {
 	$id_user     = is_numeric( $_GET['id_user'] ) ? $_GET['id_user'] : die();
	$id_user     = (int)$id_user;
	$error       = 0;
	
	 /* 
	 **
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj          = new AjaxRequest();
	$chkUser      = $obj->checkUser( $id_user ) ? 1 : 0;
	
	if( $chkUser === 0 ) {
		 echo json_encode( array ( 'html' => $_SESSION['LANG']['error'], 'status' => 0 ) ); 
		$error = 1;
	}
	$getData         = $obj->infoUserLive( $id_user );
    $count           = count( $getData );
	$followingActive = $obj->checkFollow( $id_user, $_SESSION['authenticated'] );
	//$dataSummary     = $obj->getTotalSummary( $id_user );
	
	// Count All posts
	$dataPosts = $obj->countAllPost( $id_user );
	// Count All Followers
	$dataFollowers = $obj->countAllFollowers( $id_user );
	// Count All Following
	$dataFollowing = $obj->countAllFollowing( $id_user );
	
	
	//<---- VERIFIED
	if( $getData->type_account == 1 ) {
		$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
	} else {
		$verified = null;
	}
	
	//<--- Desc User
	if( $getData->bio != '' ) {
		$descUser = '<div class="desc_popout"><p class="bio_popout margin-zero">'. _Function :: checkTextNoLine( $getData->bio ) .'</p></div>';
	} else {
		$descUser = null;
	}
	/*
	 *--------------------
	 *       DATA
	 *-------------------- 
	 */
	 if( $getData->cover_image != '' ) {
	 	$cover = 'url('. URL_BASE .'thumb/550-550-public/cover/'. $getData->cover_image .') center center no-repeat';
	 	$urlProfile = '<a class="linkCoverLarge" href="'.URL_BASE . $getData->username . '"></a>';
		$height = null;
	 } else {
	 	$cover = null;
		$urlProfile = null;
		$height = 'height:80px;';
	 }
	 /* Following Active */
	 if( $followingActive[0]['status'] == 1 ){
	 	$follows_you = '<span class="isFollow">'.$_SESSION['LANG']['follows_you'].'</span>';
	 } else {
	 	$follows_you = null;
	 }
		
			   			
if( $count != 0 && $error == 0 ) {
	
	$output = '<div class="cover_popout" style=" background: '. $cover . ' '. $getData->color_link .'; ' .$height .'">
	' . $urlProfile . '
			<a href="'. URL_BASE . $getData->username.'">
				<img class="avatarPopout" src="'. URL_BASE .'thumb/160-160-public/avatar/'. $getData->avatar .'" width="80" />
			</a>
		</div>
	<div class="details_user">
		<div class="name_user">
			<span class="username_popout">
				<span class="h1_title">
					<a href="'. URL_BASE . $getData->username.'">
						'. $getData->name . '
					</a>
					</span> 
					'. $verified .'
				<strong>@'. $getData->username .'</strong>
				'.$follows_you.'
			</span>
		</div><!-- Name User -->
		'. $descUser .'
		<ul class="user_data_popout">
			<span style="padding: 0 20px; float: left;">
			<li>
				<span class="container_data_profile_popout grid_first_popout">
					<a href="'. URL_BASE . $getData->username.'">
					<span class="countData_popout">'. _Function :: formatNumber( $dataPosts->totalPosts ).'</span>
					<span class="title_data_popout">'. $_SESSION['LANG']['posts'] .'</span>
					</a>
				</span>
			</li>
			<li>
				<span class="container_data_profile_popout">
					<a href="'. URL_BASE . $getData->username.'/followers">
					<span class="countData_popout">'._Function :: formatNumber( $dataFollowers->totalFollowers ).'</span>
					<span class="title_data_popout">'. $_SESSION['LANG']['followers'] .'</span>
					</a>
				</span>
			</li>
			<li class="last_li">
				<span class="container_data_profile_popout">
					<a href="'. URL_BASE . $getData->username.'/following">
						<span class="countData_popout">'._Function :: formatNumber( $dataFollowing->totalFollowing ).'</span>
						<span class="title_data_popout">'. $_SESSION['LANG']['following'] .'</span>
					</a>
				</span>
			</li>
			</span>
		</ul>
	</div>';
	
	$arrayJson = array ( 'html' => $output, 'status' => 1 );
	
    echo json_encode( $arrayJson ); 
	
   } else if( $count == 0 && $error == 0 ) {
   	 echo json_encode( array ( 'html' => $_SESSION['LANG']['error'], 'status' => 0 ) ); 
   }
  }//<--- isset( $_GET )
}//<---isset ( $_GET['id_user'])
 ?>