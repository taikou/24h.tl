<?php
session_start();
error_reporting(0);
if ( 
	 isset( $_POST['id_reply'] ) 
	 && !empty( $_POST['id_reply'] )
	 && isset( $_POST['reply_msg'] )
) {
if ( isset( $_SESSION['authenticated'] ) ) {
		/*
		 * --------------------------
		 *   Require File
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
		$obj                = new AjaxRequest();
		$_POST['id_reply']  = is_numeric( $_POST['id_reply'] ) ? $_POST['id_reply'] : die();
		$_POST['reply_msg'] = trim( $_POST['reply_msg'] );
		$infoUser           = $obj->infoUserLive( $_SESSION['authenticated'] );
		$admin              = $obj->getSettings();
		$verifiedUserBlock  = $obj->checkUserBlock( $_POST['id_reply'], $_SESSION['authenticated'] );
		
		// IF Message is Null
		if( mb_strlen( trim( $_POST['reply_msg'] ), 'utf8' ) == 0 ) {
			return false;
		}
		//Knowing whether the user is locked
		if( $verifiedUserBlock[0]['status'] == 1 ) {
			return false;
		}
		
		//<-------- *  * --------->
		if( mb_strlen( $_POST['reply_msg'], 'utf8' ) > $admin->message_length  )
		{
			$_POST['reply_msg'] = _Function::cropStringLimit( $_POST['reply_msg'], $admin->post_length );
			
		} else {
			$_POST['reply_msg'] = $_POST['reply_msg'];
		}
					
		$query = $obj->sendMessageId();
		if( $query  ){
			$infoUserMsg = $obj->infoUserLive( $_POST['id_reply'] );
			if( $infoUserMsg->push_notification_msg == 1 ) {
				//PUSHで送る
				$user_id=$_POST['id_reply'];
				$title=$_SESSION['LANG']['push_receivemsg_title'];
				$body='@'.$infoUser->username.$_SESSION['LANG']['push_receivemsg_body'];
				$url='https://24h.tl/messages/';
				$imgurl='https://24h.tl/public/avatar/'.$infoUser->avatar;
				$res=_Function::send_push($user_id, $title, $body, $url, $imgurl);
				error_log('--------response:'.$res);
			}
		}
		
		//============ VERIFIED
		if( $infoUser->type_account == '1' ) {
			$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
		} else {
			$verified = null;
		}
					
		if( !empty( $query ) ) {
		 ?>
		 
		 <!-- ******* media li-group ********  -->
				<li class="media li-group list-group-item border-group" data="<?php echo $query; ?>">
				  	<a class="pull-left" href="<?php echo URL_BASE.$infoUser->username ?>">
				    	<img class="media-object img-circle" src="<?php echo URL_BASE.'thumb/80-80-public/avatar/'.$infoUser->avatar; ?>" width="40">
				  	</a>
				  	<div class="media-body">
						<div class="pull-right small">
							<a href="javascript:void(0);" class="link-post showTooltip removeMsg" data="<?php echo $query; ?>" title="<?php echo $_SESSION['LANG']['delete']; ?>" data-toggle="tooltip" data-placement="left">
								<i class="glyphicon glyphicon-trash"></i>
								</a>
							</div>
						<h6 class="media-heading"><a href="<?php echo URL_BASE.$infoUser->username ?>" class="username-title"><strong><?php echo stripslashes( $infoUser->name ).$verified; ?></strong>
						</a> <small style="color: #999;">@<?php echo $infoUser->username ?></small>
						</h6>
				    	<p class="list-grid-block p-text"><?php echo _Function::checkText( $_POST['reply_msg'] ); ?></p>
				  		<span class="small sm-font sm-date timestamp timeAgo" data="<?php echo date('c', time() ); ?>"></span><!-- small sm-font sm-date -->
		     </div><!-- media-body -->
	    </li><!-- ******* media li-group ********  -->
	    
		 
		 <?php
	}//<---
	else {
		return false;
	}
  }//<-- SESSION
}//<-- if token id
?>