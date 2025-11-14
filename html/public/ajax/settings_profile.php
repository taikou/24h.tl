<?php
session_start();
error_reporting( 0 );
if (
	isset( $_POST[ 'name' ] ) &&
	!empty( $_POST[ 'name' ] )
) {

	/*
	 * --------------------------
	 *   Require/Include Files
	 * -------------------------
	 */
	require_once( '../../class_ajax_request/classAjax.php' );
	include_once( '../../application/functions.php' );
	include_once( '../../application/DataConfig.php' );


	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj = new AjaxRequest();
$old_uinfo=$obj->getUserInfo($_SESSION['authenticated']);

	$_POST[ 'name' ] = _Function::spaces( trim( $_POST[ 'name' ] ) );
	$_POST[ 'location' ] = _Function::spaces( trim( strip_tags( $_POST[ 'location' ] ) ) );
	$_POST[ 'website' ] = _Function::spaces( trim( $_POST[ 'website' ] ) );
	$_POST[ 'sns_instagram_id' ] = _Function::spaces( trim( $_POST[ 'sns_instagram_id' ] ) );
	$_POST[ 'sns_twitter_id' ] = _Function::spaces( trim( $_POST[ 'sns_twitter_id' ] ) );
	$url = $_POST[ 'website' ];
	$_POST[ 'website' ] = trim( $_POST[ 'website' ], '/' );
	$_POST[ 'bio' ] = _Function::checkTextDb2( trim( $_POST[ 'bio' ] ) );
	$admin = $obj->getSettings();

	//<-------- * Cutting chain if greater than post_length  * --------->
	if ( strlen( utf8_decode( $_POST[ 'bio' ] ) ) > $admin->post_length*10 ) {
		$_POST[ 'bio' ] = _Function::cropStringLimit( $_POST[ 'bio' ], $admin->post_length*10 );

	}

	if ( $_POST[ 'name' ] == '' ||
		strlen( utf8_decode( $_POST[ 'name' ] ) ) < 2 ||
		strlen( utf8_decode( $_POST[ 'name' ] ) ) > 20
	) {
		echo json_encode( array( 'response' => $_SESSION[ 'LANG' ][ 'full_name_error' ] ) );
	} else if ( !filter_var( $url, FILTER_VALIDATE_URL ) && $url != '' ) {
		echo json_encode( array( 'response' => $_SESSION[ 'LANG' ][ 'url_not_valid' ] ) );
	} else if ($_POST['sns_instagram_id'] and !preg_match('/^[0-9a-zA-Z_]{3,24}$/',$_POST['sns_instagram_id'])){
		echo json_encode( array( 'response' => 'invalid Instagram ID'));
	}else if ($_POST['sns_twitter_id'] and !preg_match('/^[0-9a-zA-Z_]{3,24}$/',$_POST['sns_twitter_id'])){
		echo json_encode( array( 'response' => 'invalid Twitter ID'));
	} else {

		$_POST[ 'name' ] = htmlspecialchars( $_POST[ 'name' ] );
		
		$_POST['check_sns_instagram']=$old_uinfo['sns_instagram_verified'];	
		$_POST['check_sns_twitter']=$old_uinfo['sns_twitter_verified'];
		
		if($_POST['sns_instagram_id'] and (!$old_uinfo['sns_instagram_verified'] or $old_uinfo['sns_instagram_id']!=$_POST['sns_instagram_id'])){
			//Instagram連携チェック
			if($_POST['check_sns_instagram']=_Function::check_sns_instagram($old_uinfo['username'],$_POST['sns_instagram_id'])){
				$check_sns_instagram='true';
			}else{
				$check_sns_instagram='false';
				$errmsg_instagram='<br>'.$_SESSION['LANG']['need_to_check_instagram'].'24h.tl/'.$old_uinfo['username'];
			}
		}elseif(!$_POST['sns_instagram_id']){
			$check_sns_instagram='false';
			$_POST['check_sns_instagram']=false;
		}
		if($_POST['sns_twitter_id'] and (!$old_uinfo['sns_twitter_verified'] or $old_uinfo['sns_twitter_id']!=$_POST['sns_twitter_id'])){
			//Instagram連携チェック
			if($_POST['check_sns_twitter']=_Function::check_sns_twitter($old_uinfo['username'],$_POST['sns_twitter_id'])){
				$check_sns_twitter='true';
			}else{
				$check_sns_twitter='false';
				$errmsg_twitter='<br>'.$_SESSION['LANG']['need_to_check_twitter'].'24h.tl/'.$old_uinfo['username'];
			}
		}elseif(!$_POST['sns_twitter_id']){
			$check_sns_twitter='false';
			$_POST['check_sns_twitter']=false;
		}

		$res = $obj->updateProfile();

		if ( $res == 1 ) {

			echo json_encode( array( 'response' => 'true', 'save_success' => $_SESSION[ 'LANG' ][ 'saved_successfully' ].$errmsg_instagram.$errmsg_twitter, 'verified_instagram'=>$check_sns_instagram, 'verified_twitter'=>$check_sns_twitter ) );
		} else {
			echo json_encode( array( 'response' => $_SESSION[ 'LANG' ][ 'error' ] ) );
		}
	} // ELSE
} // IF POST ISSET
?>