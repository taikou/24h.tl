<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset']) 
		&& isset ( $_POST['number']) 
		&& isset ( $_POST['_userId'] )
	
) {
  if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" ) {
   	
   	/*
	 * ---------------------------------------
	 *   Valid $offset && Valid $postnumbers
	 * ---------------------------------------
	 */
	$offset                 = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
	$postnumbers            = is_numeric($_POST['number']) ? $_POST['number'] : die();
	
	/*
	 * ---------------------------------------
	 *   Query > ID || Query < ID
	 * ---------------------------------------
	 */
	if( $_POST['query'] == 1 ) {
		$query = '<';
	} else {
		$query = '>';
	}
	
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
	$obj              = new AjaxRequest();
	$infoSessioUsr    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response         = $obj->search( $_POST['_userId'], ' 
	&& P.status = "1" 
	&& U.status = "active"
	&& P.date > (utc_timestamp() - interval 1 day)
	&& P.id '.$query.' '.$offset.'',
	'GROUP BY P.id ORDER BY P.id DESC', 
	'LIMIT '.$postnumbers, 
	$_SESSION['authenticated'] 
	);
		
	 $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$idPost  = $key['id'];
				$_idUser = $key['user_id'];
				
			 	if( $key['video_site'] != '' && $key['photo'] == '' ) {
					$typeMedia            = $_SESSION['LANG']['video'];
					$icon                 = '<i class="video_img_sm icons"></i>';
				} else if( $key['video_site'] == '' && $key['photo'] != '' ) {
					$typeMedia            = $_SESSION['LANG']['image'];
					$icon                 = '<i class="ico_img_sm icons"></i>';
				} else if( $key['url_soundcloud'] != '' ) {
					$typeMedia             = null;
			        $icon                  = '<i class="icon_song_min icons"></i>';
					
				} else {
					$typeMedia            = null;
					$icon                 = null;
				}
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				}
				//============ FAVORITES
				if( $key['favoriteUser'] == 1 ) {
					$classFav     = 'favorited';
					$spanFav      = ' title="'.$_SESSION['LANG']['trash'].' '.$_SESSION['LANG']['favorite'].'"';
					$spanAbsolute = '<span class="add_fav"></span>';
					$textFav      = $_SESSION['LANG']['favorited'];
				} else  {
					$classFav     = null;
					$spanFav      = null;
					$spanAbsolute = null;
					$textFav      = $_SESSION['LANG']['favorite'];
				}
				/*
				 * -------------------------------------------------
				 *      If the picture is larger than 440 pixels, 
				 *      show the thumbnail
				 * -------------------------------------------------
				 */
				$widthPhoto = _Function::getWidth( URL_BASE.'upload/'.$key['photo'] ); 
				
				if( $widthPhoto >= 600 ) {
					$thumbPic = 'thumb/600-600-';
				} else {
					$thumbPic = null;
				}
				/* Url */
				$urlStatus = URL_BASE.$key['username'].'/status/'.$key['id'];
				
				$activeRepost = $obj->checkRepost( $key['id'], $_SESSION['authenticated'] );
				
				//============ REPOST SESSION CURRENT
				if( $activeRepost == 1  ) {
					$spanRepost   = ' repostedSpan';
					$textRepost   = $_SESSION['LANG']['reposted'];
				} else  {
					$spanRepost   = null;
					$textRepost   = $_SESSION['LANG']['repost'];
				}
				/*
				 * -------------------------------------
				 *  POST DETAILS / EXPAND / FAVS ETC
				 * -------------------------------------
				 */
				include( 'post_details.php' );
			}//<<<-- Foreach
		  endif;  //<<<--- $countPosts != 0
     }//<--
}//<-- ISSET
?>