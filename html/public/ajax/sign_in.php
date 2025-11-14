<?php
session_start();
//error_reporting(1);
//phpinfo();exit;
if ( 
		isset ( $_POST['user'] ) 
		&& !empty( $_POST['user'] ) 
		&& isset ( $_POST['password'] ) 
) {
if ( !( $_SESSION['authenticated'] ) or $_GET['multilogin'] ) {
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
	   $obj = new AjaxRequest();
	  
	  $_POST['user']      = trim( _Function::spaces( $_POST['user'] ) );
	  $_POST['password']  = trim( _Function::spaces( $_POST['password'] ) );
	  $emailAddress       = $_POST['user'];
		if ( $_POST['user'] == '' || strlen( $_POST['user'] ) == 0 ) {
				
			echo '<strong>Field</strong> is required.';
		} else if ( preg_match( '/[^a-z0-9\_]/i',$_POST['user'] ) && !filter_var( $emailAddress, FILTER_VALIDATE_EMAIL ) ) {
				
			echo $_SESSION['LANG']['username_email_not_invalid'];
		} else if ( mb_strlen( $_POST['password'], 'utf8' ) < 5 || mb_strlen( $_POST['password'], 'utf8' ) > 20 ) {
				
			echo $_SESSION['LANG']['pass_not_invalid'];
		} else {
			
			 $res    = $obj->signIn();
			 if( isset( $res['id'] ) ){
			 	if($_SESSION['authenticated']){
					//マルチログイン
					$_SESSION['multilogin'][$_SESSION['authenticated']]=$_SESSION['username'];
				}
			// 	echo 'True';
			 	$_SESSION['username']      = $res['username'];
			 	$_SESSION['authenticated'] = $res['id'];
				$_SESSION['lang_user']     = $res['language'];
				
				$time = time() + 60*60*24*1000;
				if( isset( $_POST['Keeplogged'] ) && $_POST['Keeplogged'] == 1 ) {	
					setcookie('socialRemember', $res['id'], $time,'/','24h.tl');
				}
				if(preg_match('/24h_timeline/',$_SERVER['HTTP_USER_AGENT'])){
					setcookie('24h_app_autosessid',session_id(),$time,'/','24h.tl');
				}
				die('True');
		} else {
		 echo  $_SESSION['LANG']['account_not_active'];
		}// ELSE
}// ELSE
 } else {
		echo '<script type="text/javascript">	
					$(document).ready(function(){
						window.location.reload();
			         });// FIN READY 
         </script>';
	}
} else {
	if($_GET['changeaccount'] and $_POST['user']){
		//マルチログインのユーザー切り替え
		if($_SESSION['multilogin'][$_POST['user']]){
			$uinfo=$obj->getUserInfo($_POST['user']);
			if($uinfo['status']=='active'){
				$_SESSION['username']=$uinfo['username'];
				$_SESSION['authenticated']=$uinfo['id'];
				$_SESSION['lang_user']=$uinfo['language'];
			}else{
				$_SESSION[$_POST['user']]=null;
			}
		}
	}else{
		echo $_SESSION['LANG']['error'];
	}
}

 ?>