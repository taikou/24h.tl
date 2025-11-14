<?php
session_start();
error_reporting(0);
if ( 
	 isset( $_POST['id'] )
	 && !empty( $_POST['id'] )
) {

if ( ( $_SESSION['authenticated'] ) ) {
	if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST"){
		
	/*
	 * --------------------------
	 *   Require File
	 * -------------------------
	 */
	require_once('../../class_ajax_request/classAjax.php');
	include_once('../../application/functions.php');
	include_once('../../application/DataConfig.php');
	require_once('../../phpmailer/PHPMailerAutoload.php');
	
	/*
	 * ----------------------
	 *   Instance Class
	 * ----------------------
	 */
	$obj            = new AjaxRequest();
	$admin          = $obj->getSettings();
	$_POST['id']    = trim( $_POST['id'] );
	$explode        = explode( '-', $_POST['id'] );
	$_POST['id']    = (int)$explode[1];
	$infoUser       = $obj->infoUserLive( $_SESSION['authenticated'] );
	$verifiedUser   = $obj->checkUser( $_POST['id'] ) ? 1 : 0;
	
	if( $verifiedUser == 1 ) {
		$infoUserFollow = $obj->infoUserLive( $_POST['id'] );
	}
	
	$messageEmail = '
			<table width="550" cellpadding="0" cellspacing="0" style="font-family:Arial,Helvetica,sans-serif; font-size: 14px; color: #666;" align="center">
	<tbody>
		<tr>
			<td width="550" height="50" align="center" style="background: #fff;">
				<img style="width: 130px;" src="'. URL_BASE .'public/img/logo.png" />
			</td>
		</tr>
		
		<tr>
			<td width="558" align="center" style="background: #FFF; line-height: 18px; padding: 10px;  border-bottom: 1px solid #DDD; border-left: 1px solid #DDD; border-right: 1px solid #DDD;">
				
				<p style="margin-bottom: 10px;">
					<strong>
						<a style="color: #FF7000;" rel="nofollow" href="'. URL_BASE .$infoUser->username.'" target="_blank">
						@'.$infoUser->username.'
						</a>
						</strong> is following you
				</p>
				
				<p style="text-align: center; font-size: 12px; padding: 5px 0 0 0;">
					<a style="color: #FFF; font-weight: bold; padding: 5px 10px; background: #FF7000; border-radius: 3px; -webkit-border-radius: 3px;" rel="nofollow" href="'. URL_BASE .$infoUserFollow->username.'/followers" target="_blank">
						View your followers &raquo;
					</a>

					<p style="text-align: center; font-size: 11px; padding: 5px 0 0 0;">
						© '.date('Y') .' Decoo
					</p>
			</td>
		</tr>
	</tbody>
</table>'; 
	
	//*** DATABASE
	$query = $obj->follow();
	
	if( $query == 1 ) {
		
		if( $infoUserFollow->email_notification_follow == 1 && $infoUserFollow->email != '' && filter_var( $infoUserFollow->email, FILTER_VALIDATE_EMAIL ) ) {
			
			//<------- * Send Email * ------------>
			$mail = new PHPMailer;
			
			/*-------------
			 * SMTP 
			 *------------
			 */
			if( SMTP_USERNAME != '' ) {
				$mail->isSMTP();          // Set mailer to use SMTP
				$mail->Host = SMTP_HOST;  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;   // Enable SMTP authentication
				$mail->Username = SMTP_USERNAME; // SMTP username
				$mail->Password = SMTP_PASSWORD; // SMTP password
				$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = SMTP_PORT;  // TCP port to connect to
			}

			$mail->CharSet = 'UTF-8';
			$mail->Encoding = 'quoted-printable';
			$mail->From = EMAIL_NOTIFICATIONS;
			$mail->FromName = $admin->title;
			$mail->addAddress($infoUserFollow->email);
			$mail->isHTML(true);
			$mail->Subject = '@'.$infoUser->username.' is following you';
			$mail->Body    = $messageEmail;
			
			$mail->send();

		}
		if( $infoUserFollow->push_notification_follow == 1 ) {
		
				//PUSHで送る
				$user_id=$_POST['id'];
				$title=$_SESSION['LANG']['push_newfollower_title'];
				$body='@'.$infoUser->username.$_SESSION['LANG']['push_newfollower_body'];
				$url='https://24h.tl/'.$infoUser->username;
				$imgurl='https://24h.tl/public/avatar/'.$infoUser->avatar;
				$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
		}
		
		 echo json_encode( array( 'status' => 1 ) );
		 
	} else if( $query == 2 ){
		 echo json_encode( array( 'status' => 2 ) );
	} else if( $query == 3 ){
		 echo json_encode( array( 'status' => 3 ) );
	} else if( $query == 4){
		echo json_encode( array('status'=>0, 'error'=> $_SESSION['LANG']['incorrectfollowpass']));
	} else {
		echo json_encode( array( 'status' => 0, 'error' => $_SESSION['LANG']['error'] ) );
	}
		}//<-- POST ISSET
  }//<-- SESSION
}//<-- if token id
?>