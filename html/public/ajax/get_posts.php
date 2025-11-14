<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset']) 
		&& isset ( $_POST['number']) 
		&& isset ( $_POST['_userId']) 
		&& !empty( $_POST['_userId'] )
	
) {
  if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" ) {
   	
   	/*
	 * --------------------------------------------------
	 *   Valid $offset, $id_user && Valid $postnumbers
	 * --------------------------------------------------
	 */
	$offset                 = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
	$postnumbers            = is_numeric($_POST['number']) ? $_POST['number'] : die();
    $id_user                = is_numeric($_POST['_userId']) ? $_POST['_userId'] : die();
	
	/*
	 * ---------------------------------------
	 *   Query > ID || Query < ID
	 * ---------------------------------------
	 */
	if( $_POST['query'] == 1 ) {
		$query = '<';
	} else  {
		$query = '>';
	}
	
	if( !$_SESSION['authenticated'] ) {
		$id_user_favs = 0;
	} else {
		$id_user_favs = $_SESSION['authenticated'];
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
	$infoUser         = $obj->infoUserLive( $id_user );
	$infoSessioUsr    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response         = $obj->getAllPosts(
		'WHERE P.user = '.$id_user.'
			&& P.status = "1"
			&& P.status_general = "1"
			&& U.status = "active"'
	.($id_user!=$_SESSION['authenticated']?'&& P.date>=utc_timestamp() - interval 1 day':'').'		  	
			&& P.id '.$query.' '.$offset .'
			GROUP BY P.id DESC ', 
		'LIMIT '.$postnumbers, 
		$id_user_favs 
	);
//固定ポスト対応

	$static_ids=$obj->getStaticPosts($id_user);

	if(count($static_ids)>0){
		$static_idlist=join(',',$static_ids); 

		$static = $obj->getAllPosts(
			'WHERE P.user = '.$id_user.'
				&& P.status = "1"
				&& P.status_general = "1"
				&& U.status = "active"
				&& P.id IN ('.$static_idlist.')		  	
				&& P.id '.$query.' '.$offset .'
				GROUP BY P.id DESC ', 
			'LIMIT '.$postnumbers, 
			$id_user_favs 
		);
		$response=array_merge($static,$response);
	}
	

	  
	  
	$checkFollow      = $obj->checkFollow( $_SESSION['authenticated'], $id_user );
	
	$_countPosts = count( $response );
	
	if( $_countPosts == 0 ) {
		$nofound = '<span class="notfound">No posts to display</span>';
	}
	$user     = $id_user;
	
	if( $infoUser->mode == 0 
		&& $checkFollow[0]['status'] == 0 
		&& $_SESSION['authenticated'] 
		!= $user  
	) {
		$response = null;
		$nofound  = null;
		$mode     = '<div class="panel-footer text-center" style="padding: 25px 0; background: url('.URL_BASE.'public/img/private.png) right bottom no-repeat;" class="notfound">
		'.$_SESSION['LANG']['profile_private'].'</div>';
	} else {
		$response = $response;
		$mode     = null;
	}
	
	$countPosts = count( $response );
   	  if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				
				if( $key['repost_of_id'] != 0 ) {
					$nameUser            = $key['name'];
					$key['type_account'] = $key['rp_type_account'];
					$key['username']     = $key['rp_username'];
					$key['name']         = $key['rp_name'];
					$key['avatar']       = $key['rp_avatar'];
					$key['user']         = $key['rp_user'];
					$idPost              = $key['id'];
					$key['id']           = $key['rp_id'];
					$_idUser             = $key['rp_id_user'];
					
				} else {
					$nameUser            = null;
					$key['type_account'] = $key['type_account'];
					$key['username']     = $key['username'];
					$key['name']         = $key['name'];
					$key['avatar']       = $key['avatar'];
					$key['user']         = $key['user'];
					$idPost              = $key['id'];
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
				}
				
				$activeRepost = $obj->checkRepost( $key['id'], $_SESSION['authenticated'] );
				
				//============ REPOST SESSION CURRENT
				if( $activeRepost == 1 ) {
					$spanRepost   = ' repostedSpan';
					$textRepost   = $_SESSION['LANG']['reposted'];
				} else  {
					$spanRepost   = null;
					$textRepost   = $_SESSION['LANG']['repost'];
				}
				
				 
				//============= 固定
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
				 * -------------------------------------------------
				 *      If the picture is larger than 440 pixels, 
				 *      show the thumbnail
				 * -------------------------------------------------
				 */
				$widthPhoto = _Function::getWidth( '../../upload/'.$key['photo'] );
				
				if( $widthPhoto >= 600  ) {
					$thumbPic = 'thumb/600-600-';
				} else  {
					$thumbPic = null;
				}
				/*
				 * -------------------------------------
				 *  POST DETAILS / EXPAND / FAVS ETC
				 * -------------------------------------
				 */
				include( 'post_details.php' );
				
				 }//<<<--- Foreach
            endif; //<<<---- $countPosts != 0
       echo $mode;	  
     }//<-- ISSET POST
}//<-- ISSET DATA
?>