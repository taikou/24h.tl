<?php
if ( 
		isset ( $_POST['full_name'] ) 
		&& !empty( $_POST['full_name'] ) 
		&& isset ( $_POST['username'] ) 
		&& !empty( $_POST['username'] ) 
		&& isset ( $_POST['email'] ) 
		&& !empty( $_POST['email'] ) 
		&& isset ( $_POST['password'] ) 
) {
 
		
	  $_POST['code']           = sha1 ( $_SERVER['REMOTE_ADDR'] . microtime() . mt_rand ( 1,100000 ) )._Function::randomString( 40, TRUE, TRUE, TRUE );
	  $_POST['email']          = trim( $_POST['email']);
	  $_POST['full_name']      = _Function::spaces( trim( $_POST['full_name']) );
	  $_POST['username']       = _Function::spaces( trim( $_POST['username'] ) );
	  $_POST['password']       = _Function::spaces( trim( $_POST['password'] ) );
	  $admin                   = $obj->getSettings();
	  $emailAddress            = $_POST['email'];
	  $_POST['email_verify']   = $settings->email_verification;
		
		if ( $_POST['full_name'] == '' 
			|| mb_strlen( $_POST['full_name'], 'utf8' ) < 2 
			|| mb_strlen( $_POST['full_name'], 'utf8' )  > 20 
		) 
		{
			echo json_encode( array( 'res' => $_SESSION['LANG']['full_name_error'], 'focus' => 'full_name' ) );
			
		} else if ( preg_match( '/[^a-z0-9\_]/i',$_POST['username'] ) ) {
			
			echo json_encode( array( 'res' => $_SESSION['LANG']['username_not_valid'], 'focus' => 'username' ) );
			
		} else if ( strlen( $_POST['username'] ) < 4 || strlen( $_POST['username'] ) > 15 ) {
			
			echo json_encode( array( 'res' => $_SESSION['LANG']['username_not_valid'], 'focus' => 'username' ) );
			
		} else if ( !filter_var( $emailAddress, FILTER_VALIDATE_EMAIL ) ) {
			
			echo json_encode( array( 'res' => $_SESSION['LANG']['email_not_valid'], 'focus' => 'email' ) );
		   
		} else if ( mb_strlen( $_POST['password'], 'utf8' ) < 5 || mb_strlen( $_POST['password'], 'utf8' ) > 20 ) {
			
			echo json_encode( array( 'res' => $_SESSION['LANG']['password'], 'focus' => 'username' ) );
			
		}  else if ( $_POST['terms'] == '' ) {
			
			echo json_encode( array( 'res' => $_SESSION['LANG']['can_not_register'], ) );
		} else {
			
			/* INSERT DATABASE */
			$res = $obj->signUp();
			
	if( isset( $res ) && $res != 'unavailable' ) {
			
			$_SESSION['authenticated'] = $res;
			$_SESSION['lang_user']     = $_SESSION['lang'];
				
		  	echo json_encode( array( 'res' => $_SESSION['LANG']['sign_up_success'], 'success' => 1 ) );

		  } else if ( $res == 'unavailable' ) {
		  	
			echo json_encode( array( 'res' => $_POST['username'].' '.$_SESSION['LANG']['username_already_use'] ) );
	    } else {
	    	echo json_encode( array( 'res' => $_SESSION['LANG']['email_already_use'].'.' ) );
	 }// ELSE
   }// ELSE
 } else {
	echo $_SESSION['LANG']['error'];
}
 ?>