<?php
session_start();
error_reporting(0);
if( 
		isset ( $_GET['offset']) 
		&& isset ( $_GET['number'])
	
)
{
if ( isset( $_SESSION['authenticated'] ) )
{
  if( isset( $_GET ) && $_SERVER['REQUEST_METHOD'] == "GET" )
   {
   	
   	/*
	 * --------------------------------------------------
	 *   Valid $offset && Valid $postnumbers
	 * --------------------------------------------------
	 */
	$offset                 = is_numeric($_GET['offset']) ? $_GET['offset'] : die();
	$postnumbers            = is_numeric($_GET['number']) ? $_GET['number'] : die();
	
	/*
	 * ---------------------------------------
	 *   Query > ID || Query < ID
	 * ---------------------------------------
	 */
	if( $_GET['query'] == 1 ) {
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
	$obj      = new AjaxRequest();
	$infoSessioUsr    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response = $obj->getAllPosts('
	WHERE P.user = '.$_SESSION['authenticated'] .'
			&& P.status = "1"
			&& P.status_general = "1"
			&& U.status = "active"'
.'&& P.date>=(utc_timestamp() - interval 1 day)'.'		  
	&& P.id '.$query.' '.$offset .'
	|| P.user 
			IN (
					SELECT following FROM followers WHERE follower = '.$_SESSION['authenticated'] .' 
					&& status = "1"
			 ) '
.'&& P.date>=(utc_timestamp() - interval 1 day) '
			.'&& P.status = "1" && U.status = "active" && P.id '.$query.' '.$offset .'
			 GROUP BY IF( P.`repost_of_id` = 0, P.`id`, P.`repost_of_id`) DESC
			 ORDER BY P.id DESC
			  ',
	 'LIMIT '.$postnumbers,
	  $_SESSION['authenticated'] 
	);
	
 $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				if( $key['repost_of_id'] != 0 ) {
					$nameUser            = $key['name'];
					$idPost              = $key['id'];
					$key['type_account'] = $key['rp_type_account'];
					$key['username']     = $key['rp_username'];
					$key['name']         = $key['rp_name'];
					$key['avatar']       = $key['rp_avatar'];
					$key['user']         = $key['rp_user'];
					$key['id']           = $key['rp_id'];
					$_idUser             = $key['rp_id_user'];
					
				} else {
					$nameUser            = null;
					$key['type_account'] = $key['type_account'];
					$idPost              = $key['id'];
					$key['username']     = $key['username'];
					$key['name']         = $key['name'];
					$key['avatar']       = $key['avatar'];
					$key['user']         = $key['user'];
					$key['id']           = $key['id'];
					$_idUser             = $key['user_id'];
					
				}
				
				/* Url */
				$urlStatus = URL_BASE.$key['username'].'/status/'.$key['id'];
	
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else  {
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
					$FavsByType=null;
					$classReaction=null;
				}
				
				$widthPhoto = _Function::getWidth( '../../upload/'.$key['photo'] );
				
				if( $widthPhoto >= 600  ) {
					$thumbPic = 'thumb/600-600-';
				} else  {
					$thumbPic = null;
				}
				$activeRepost = $obj->checkRepost( $key['id'], $_SESSION['authenticated'] );
				
				//============ REPOST SESSION CURRENT
				if( $activeRepost == 1  ) {
					$spanRepost   = ' repostedSpan';
					$textRepost   = $_SESSION['LANG']['reposted'];
				} else  {
					$spanRepost   = null;
					$textRepost   = $_SESSION['LANG']['repost'];
				}
				 
				//============= 固定
				 $static_ids=$obj->getStaticPosts($_SESSION['authenticated']);

				 if( in_array($idPost,$static_ids,1)){
					 $is_static=true;
					 $classStatic=' staticed';
					 $dataActionStatic='remove';
				 }else{
					 $is_static=false;
					 $classStatic = null;
					 $dataActionStatic='set';
				 }
				/*				
				/*
				 * -------------------------------------
				 *  POST DETAILS / EXPAND / FAVS ETC
				 * -------------------------------------
				 */
				include( 'post_details.php' );
					
				 }//<<-- Foreach 
			endif; 
     }//<-- SESSION
  }//<-- if token id
}//<-- ISSET
?>