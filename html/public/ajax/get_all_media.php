<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset'] ) 
		&& isset ( $_POST['number'] ) 
		&& isset ( $_POST['_userId'] ) 
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
	$obj         = new AjaxRequest();
	$infoUser    = $obj->infoUserLive( $id_user );
	$infoSessioUsr    = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response    = $obj->getAllMedia( $id_user, ' && P.id '.$query.' '.$offset .'', 'LIMIT '.$postnumbers );
	$checkFollow = $obj->checkFollow( $_SESSION['authenticated'], $id_user );
	
	$_countTotal = count( $response );
	$user        = $id_user;
	
	if( $_countTotal == 0 ) {
		$nofound = '<span class="notfound">No result</span>';
	}
	
	if( $infoUser->mode == 0 && $checkFollow[0]['status'] == 0 && $_SESSION['authenticated'] != $user ) {
		$response = null;
		$nofound  = null;
		$mode     = '<div class="panel-footer text-center" style="padding: 25px 0; background: url('.URL_BASE.'public/img/private.png) right bottom no-repeat;" class="notfound">
		'.$_SESSION['LANG']['profile_private'].'</div>';
	}

	else {
		$response = $response;
		$mode     = null;
	}
	
	?>
	<?php $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$_idUser = $key['id_user'];
			 	
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
			}//<<--- Foreach
		  endif; // != 0
	 echo $mode;  
  }//<-- ISSET POST
}//<-- ISSET DATA
?>