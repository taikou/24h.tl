<?php
session_start();
error_reporting(0);
if ( 
	 isset( $_POST['id_reply'] ) 
	 && !empty( $_POST['id_reply'] ) 
	 && isset( $_POST['token_reply'] ) 
	 && !empty( $_POST['token_reply'] ) 
	 && isset( $_POST['reply_post'] )
)
{
if ( isset( $_SESSION['authenticated'] ) ){
	
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
	$obj                  = new AjaxRequest();
	
	$_POST['id_reply']    = is_numeric( $_POST['id_reply'] ) ? $_POST['id_reply'] : die();
	$_POST['token_reply'] = trim( $_POST['token_reply'] );
	$_POST['reply_post']  = trim( _Function::checkTextDb( $_POST['reply_post'] ) );
	$infoUser             = $obj->infoUserLive( $_SESSION['authenticated'] );
	$admin                = $obj->getSettings();
	
	//<-------- *  * --------->
	if( strlen( utf8_decode( $_POST['reply_post'] ) ) > $admin->post_length  ) {
		$_POST['reply_post'] = _Function::cropStringLimit( $_POST['reply_post'], $admin->post_length );
		
	}
	
	if( strlen( utf8_decode( $_POST['reply_post'] ) ) == 0 ) {
		return false;
	}
	if( $infoUser->type_account == 1 ) {
		 $verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>'; 
	} else {
		 $verified = null; 
	}
	
	/*
	 * ----------------------
	 *   Send Reply
	 * ----------------------
	 */
	$query = $obj->sendReply();
	
	if( !empty( $query ) ) {
		 ?>
		 <!-- SPAN REPLY -->
		 <div class="media li-group reply-list">
				  	<a class="pull-left openModal" data-id="<?php echo $infoUser->id; ?>" href="<?php echo URL_BASE.$infoUser->username; ?>">
				    	<img class="media-object img-circle" src="<?php echo URL_BASE; ?>thumb/80-80-public/avatar/<?php echo $infoUser->avatar; ?>" width="40">
				  	</a>
				  	<div class="media-body">
						
							<div class="pull-right small"> 
								<a href="javascript:void(0);" data="<?php echo $query; ?>" class="link-post showTooltip removeReply" title="<?php echo $_SESSION['LANG']['delete']; ?>" data-toggle="tooltip" data-placement="top"> 
								<i class="glyphicon glyphicon-remove size-icon"></i> 
								</a> 
								</div>
							
						<h6 class="media-heading">
							<a href="<?php echo URL_BASE.$infoUser->username; ?>" class="username-posts openModal" data-id="<?php echo $infoUser->id; ?>"><strong><?php echo $infoUser->name; ?></strong> <?php echo $verified; ?></a> <small class="username-ui">@<?php echo $infoUser->username; ?></small>
							</h6>
				    	<p class="list-grid-block p-text">
				    		<?php echo _Function::checkText( $_POST['reply_post'] ); ?>
				    	</p>
				    	<span class="timestamp timeAgo small sm-font sm-date" data="<?php echo date('c',  time() ); ?>"></span><!-- small sm-font sm-date -->
				  	</div><!-- media-body -->
				</div><!-- media -->
		 <?php
	} else  {
		return false;
	}
  }//<-- SESSION
}//<-- if token id
?>
