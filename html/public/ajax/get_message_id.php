<?php
session_start();
error_reporting(0);
if(  isset ( $_POST['_userId'] )  && !empty( $_POST['_userId'] ) ) {
	//<<<<<------ 
	if( $_POST['_userId'] == $_SESSION['authenticated'] ) {
		return false;
	}
	
  if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['authenticated'] ) {
   	
   	/*
	 * -----------------------
	 *   Valid $id_user 
	 * -----------------------
	 */
    $id_user = is_numeric($_POST['_userId']) ? $_POST['_userId'] : die();
	

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
	$obj               = new AjaxRequest();
	$response          = $obj->getMessageId( $id_user, $_SESSION['authenticated'] );
	$countMessagesID   = $obj->countMessagesID( $id_user, $_SESSION['authenticated'] );
	
	//<<<<<----- Verified User
	$verified = $obj->checkUser( $id_user ) ? 1 : 0;
		
		if( $verified == 0 ) {
			return false;
		}
		
	 $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
		
		//<----- Count messages > 5 
		if( $countMessagesID > 5 ) :
			 ?>
			 <div class="panel-body padding-bottom-zero" id="loadMsgPanel">
			 	<button style="background-color: #FFF;" data-total="<?php echo $countMessagesID; ?>" type="button" id="load-msg" class="btn btn-default btn-border btn-df btn-sm btn-block margin-zero btn-load loadMessagesOld">
			 	<i class="icon-arrow-up2 myicon-right"></i> <?php echo $_SESSION['LANG']['read_more'].' '.strtolower( $_SESSION['LANG']['messages'] ); ?>
			 	</button>
			 </div>
			 
			 <?php
			 endif; //<--- $countMessagesID > 5
			 
   		 //<---------********** FOREACH -----********>
			 foreach ( $response as $key )  {
			 	
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				} if( $key['id_user'] == $_SESSION['authenticated'] ) {
					$remove = '<i title="'.$_SESSION['LANG']['delete'].'" style="left:-15px; top: 35px;" data="'.$key['id'].'" class="trash_ico_reply removeMsg"></i>';
					$class = ' class="msg_id_user"';
				} else {
					$class = null;
					$remove = '<i title="'.$_SESSION['LANG']['delete'].'" data="'.$key['id'].'" class="trash_ico_reply removeMsg"></i>';
				}
				?>
				<!-- POSTS -->
				
				<!-- ******* media li-group ********  -->
				<li class="media li-group list-group-item border-group list-slimscroll" data-user="<?php echo $id_user; ?>" data="<?php echo $key['id']; ?>">
				  	<a class="pull-left" href="<?php echo URL_BASE.$key['username'] ?>">
				    	<img class="media-object img-circle" src="<?php echo URL_BASE.'thumb/80-80-public/avatar/'.$key['avatar']; ?>" width="40">
				  	</a>
				  	<div class="media-body">
						<div class="pull-right small">
							<a href="javascript:void(0);" class="link-post showTooltip removeMsg" data="<?php echo $key['id']; ?>" title="<?php echo $_SESSION['LANG']['delete']; ?>" data-toggle="tooltip" data-placement="left">
								<i class="glyphicon glyphicon-trash"></i>
								</a>
							</div>
						<h6 class="media-heading"><a href="<?php echo URL_BASE.$key['username'] ?>" class="username-title"><strong><?php echo stripslashes( $key['name'] ).$verified; ?></strong>
						</a> <small style="color: #999;">@<?php echo $key['username']; ?></small>
						</h6>
				    	<p class="list-grid-block p-text"><?php echo _Function::checkText( $key['message'] ); ?></p>
				  		<span class="small sm-font sm-date timestamp timeAgo" data="<?php echo date('c', strtotime( $key['date'] ) ); ?>"></span><!-- small sm-font sm-date -->
		     </div><!-- media-body -->
	    </li><!-- ******* media li-group ********  -->
	    <?php }//<<<--- Foreach 
		   		endif;
     }//<-- ISSET POST
}//<-- ISSET DATA
?>