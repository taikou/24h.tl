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
	} else {
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
	$response         = $obj->getFollowing(
	'WHERE F.follower = '. $id_user .' && F.id '.$query.' '.$offset .'', 'LIMIT '.$postnumbers, $id_user_favs );
	$checkFollow       = $obj->checkFollow( $_SESSION['authenticated'], $id_user );
	
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
	} else {
		$response = $response;
		$mode     = null;
	}
	?>
	<?php $countPosts = count( $response );
   		 if( $countPosts != 0 ) : 
			 foreach ( $response as $key ) {
			 	
				$countryUser = $obj->getCountryUser( $key['id'] );
				 
				//============ VERIFIED
				if( $key['type_account'] == '1' ) {
					$verified = ' <i class="fa fa-check-circle verified verified-min showTooltip" title="'.$_SESSION["LANG"]["verified"].'" data-toggle="tooltip" data-placement="right"></i>';
				}
				else {
					$verified = null;
				}
				$checkBlock      = $obj->checkUserBlock( $key['id'], $_SESSION['authenticated'] );
				$checkBlocked    = $obj->checkUserBlock( $_SESSION['authenticated'], $key['id'] );
				
				?>
				<li class="media border-group hoverList li-group list-group-item" data="<?php echo $key['id_follow']; ?>">
					<!-- ******** Media ****** -->  
		    	  	<div class="media li-group">
		                    <div class="pull-left">
		                       <a href="<?php echo URL_BASE.$key['username']; ?>" class="openModal" data-id="<?php echo $key['id']; ?>">
		                       	<img src="<?php echo URL_BASE."thumb/200-200-public/avatar/".$key['avatar']; ?>" alt="Avatar" class="media-object img-rounded" width="100">
		                       </a>
		                    </div>
		                    <div class="media-body clearfix text-overflow">
		                       <strong class="media-heading">
		                       		<a href="<?php echo URL_BASE.$key['username']; ?>" data-id="<?php echo $key['id']; ?>" class="openModal username-title-2">
		                       			<?php echo stripslashes( $key['name'] ).$verified; ?>
		                       			</a>
		                       		<small style="color: #999;">@<?php echo $key['username']; ?></small>
		                       	</strong>
		                       <p class="text-col">
		                       	
		                       	<?php if( $key['country'] != 'xx' && empty( $key['bio']  ) && $countryUser->country != FALSE ) : ?>
		                       	<small><i class="fa fa-map-marker myicon-right"></i> <?php echo $countryUser->country ?></small>
		                        <?php endif; ?>
		                        
		                        <small><?php echo _Function::checkTextNoLine( $key['bio'] ); ?></small>
		                       </p>
		                       
				              <div class="btn-block">
		                       	
		                      <?php if( $checkBlock[0]['status'] == 0 ): ?>	
                          
	                          <?php if(  isset( $_SESSION['authenticated'] ) && $_SESSION['authenticated'] != $key['id'] && $key['followActive'] == 0 ): ?>
	                          <button data-username="<?php echo $key['username']; ?>" data-id="<?php echo _Function::randomString( 10, FALSE, TRUE, FALSE ).'-'.$key['id']; ?>" data-follow="<?php echo $_SESSION['LANG']['follow']; ?>" data-unfollow="<?php echo $_SESSION['LANG']['unfollow']; ?>" data-following="<?php echo $_SESSION['LANG']['following']; ?>" type="button" class="btn btn-border btn-xs btn-default followBtn" data-original-title="Request send"><i class="icon-user3 myicon-right"></i> <?php echo $_SESSION['LANG']['follow']; ?></button>
	                       	   <?php endif; ?>
	                       	   
	                       	   <?php if(  isset( $_SESSION['authenticated'] ) && $_SESSION['authenticated'] != $key['id'] && $key['followActive'] == 1 ): ?>
	                       	   <button data-username="<?php echo $key['username']; ?>" data-id="<?php echo _Function::randomString( 10, FALSE, TRUE, FALSE ).'-'.$key['id']; ?>" data-follow="<?php echo $_SESSION['LANG']['follow']; ?>" data-unfollow="<?php echo $_SESSION['LANG']['unfollow']; ?>" data-following="<?php echo $_SESSION['LANG']['following']; ?>" type="button" class="btn btn-border btn-xs btn-info follow_active followBtn" data-original-title="Request send"><i class="icon-user3 myicon-right"></i> <?php echo $_SESSION['LANG']['following']; ?></button>
	                       	   <?php endif; ?>
	                       	   
	                       	  <?php endif;//<<-- Blocked ?>
		                       	
		                       </div><!-- btn-block -->
		                       
		                    </div><!-- media-body -->
		        	 </div><!-- ******** Media ****** -->
				</li> <?php }//<<--- Foreach 
		   endif;
				echo $mode;  
     }//<-- ISSET POST
}//<-- ISSET DATA
?>