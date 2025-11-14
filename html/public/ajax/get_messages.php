<?php
session_start();
error_reporting(0);
if( 
		isset ( $_POST['offset']) 
		&& isset ( $_POST['number']) 
		&& isset ( $_POST['_userId']) 
		&& !empty( $_POST['_userId'] )
	
) {
  if( isset( $_POST ) && $_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['authenticated'] ) {
   	
   		/*
	 * --------------------------------------------------
	 *   Valid $offset && Valid $postnumbers
	 * --------------------------------------------------
	 */
	$offset                 = is_numeric($_POST['offset']) ? $_POST['offset'] : die();
	$postnumbers            = is_numeric($_POST['number']) ? $_POST['number'] : die();
	
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
	$obj               = new AjaxRequest();
	$infoUser          = $obj->infoUserLive( $_SESSION['authenticated'] );
	$response          = $obj->getMessages( $_SESSION['authenticated'], ' && id '.$query.' '.$offset .'',null );
	?>
	<?php $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
				
				if( $key['from'] == $_SESSION['authenticated'] && $key['username'] == $infoUser->username ) {
			 		$key['name']         = $key['name2'];
					$key['username']     = $key['username2'];
					$key['avatar']       = $key['avatar2'];
					$key['type_account'] = $key['type_account2'];
					$ID                  = $key['id_2'];
			 		$sendTo              = '<i class="icon-undo2"></i> ';
			 	} else if( $key['from'] == $_SESSION['authenticated'] && $key['username2'] == $infoUser->username ) {
			 		$key['name']         = $key['name'];
					$key['username']     = $key['username'];
					$key['avatar']       = $key['avatar'];
					$key['type_account'] = $key['type_account'];
					$ID                  = $key['id_user'];
			 		$sendTo              = '<i class="icon-undo2"></i> ';
			 	} else if( $key['from'] != $_SESSION['authenticated'] && $key['username'] == $infoUser->username ) {
					$key['name']         = $key['name2'];
					$key['username']     = $key['username2'];
					$key['avatar']       = $key['avatar2'];
					$key['type_account'] = $key['type_account2'];
					$ID                  = $key['id_2'];
					$sendTo              = null;
				} else if( $key['from'] != $_SESSION['authenticated'] && $key['username2'] == $infoUser->username ) {
					$key['name']         = $key['name'];
					$key['username']     = $key['username'];
					$key['avatar']       = $key['avatar'];
					$key['type_account'] = $key['type_account'];
					$ID                  = $key['id_user'];
					$sendTo              = null;
				}
				
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				} else {
					$verified = null;
				}
				
				if( mb_strlen( $key['message'], 'utf8' ) > 45 ) {
				 	$message = _Function::cropString( $key['message'], 45 );
				 } else {
				 	$message = $key['message'];
				 }
				 
				 /* New - Readed */
				 if( $key['status'] == 'new' && $key['from'] != $_SESSION['authenticated'] )  {
				 	$styleStatus = ' unread-msg'; 
				 } else {
					 $styleStatus = null; 
				 }
				?>
				<!-- POSTS -->
				<li class="li-group hoverList" data-id="<?php echo strtotime( $key['date'] ); ?>" data="<?php echo $key['id']; ?>">
	      		<a data-username="@<?php echo $key['username']; ?>" data-reply="<?php echo $_SESSION['LANG']['reply']; ?>" data="<?php echo $ID; ?>" href="messages/#" class="see_msg list-group-item border-group<?php echo $styleStatus; ?>">
	                         <div class="media">
	                            <div class="pull-left">
	                               <img src="<?php echo URL_BASE.'thumb/96-96-public/avatar/'.$key['avatar']; ?>" alt="Image" class="border-image-profile-2 media-object img-circle image-dropdown" width="48">
	                            </div>
	                            <div class="media-body clearfix">
	                               <div class="pull-right small">
	                               	<span class="timestamp timeAgo myicon-right" data="<?php echo date('c', strtotime( $key['date'] ) ); ?>"></span>
	                               	<i title="<?php echo $_SESSION['LANG']['delete']. ' ' .$_SESSION['LANG']['messages']; ?>" data="<?php echo $ID; ?>" class="glyphicon glyphicon-trash removeAllMsg showTooltip" data-toggle="tooltip" data-placement="left"></i>
	                               	</div>
	                               
	                               <div class="media-nowrap">
	                               	<strong class="media-heading"><?php echo stripslashes( $key['name'] ).$verified; ?> <small style="color: #999;">@<?php echo $key['username']; ?></small></strong>
	                               </div>
	                               
	                               <p class="text-col">
	                                  <small><?php echo $sendTo._Function::checkTextMessages( $message ); ?></small>
	                               </p>
	                            </div>
	                         </div>
	                      </a>
	          	</li>
	          	<?php }//<<--- Foreach 
		 endif;
     }//<-- ISSET POST
}//<-- ISSET DATA
?>